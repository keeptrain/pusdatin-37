<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use App\States\LetterStatus;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use App\Models\letters\LettersMapping;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModalConfirmation extends Component
{
    public $showModal = false;

    #[Locked]
    public int $letterId;

    public Letter $letter;

    public $status = '';

    public $notes = '';

    public bool $activeRevision;

    public $revisionParts = [];

    public $revisionNotes = [];

    public $showPart = false;

    public $showNotes = false;

    public function rules()
    {
        $rules = [
            'status' => [
                'required',
                'string',
                Rule::in($this->getStatusMap()->keys()),
            ],
        ];

        if ($this->status === 'replied') {
            if ($this->activeRevision) {
                $rules['status'][] = function ($attribute, $value, $fail) {
                    $fail('Masih ada revisi aktif yang belum diselesaikan. Tidak dapat membuat revisi baru.');
                };
            }

            $rules['revisionParts'] = [
                'required',
                'array',
                'min:1'
            ];

            foreach ($this->revisionParts as $index => $partName) {
                $rules["revisionNotes.$partName"] = ['required', 'string'];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'status.required' => 'Status surat tidak boleh kosong',
            'revisionParts.required' => 'Butuh bagian yang harus di revisi'
        ];
    }

    public function mount()
    {
        $this->showModal = true;
    }

    public function openModal(int $id, $activeRevision)
    {
        $this->letterId = $id;
        $this->activeRevision = $activeRevision;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function openNotesClosePart()
    {
        $this->showNotes = true;
        $this->showPart = false;
    }

    public function openPartCloseNotes()
    {
        $this->showNotes = false;
        $this->showPart = true;
    }

    public function getStatusMap()
    {
        $allowedStates = ['Approved', 'Replied', 'Rejected'];

        return collect(LetterStatus::getStateMapping())->filter(
            fn($class) => in_array(class_basename($class), $allowedStates)
        )->mapWithKeys(
            fn($class) => [
                strtolower(class_basename($class)) => $class,
            ]
        );
    }

    private function getStatusFromInput()
    {
        $map = $this->getStatusMap();

        $stateClass = $map[$this->status];

        return $stateClass;
    }

    public function updateRevisionFromMapping(int $letterId, string $partName, string $note)
    {
        $mapping = LettersMapping::where('letter_id', $letterId)
            ->where('letterable_type', LetterUpload::class)
            ->whereHasMorph('letterable', [LetterUpload::class], function ($query) use ($partName) {
                $query->where('part_name', $partName);
            })
            ->first();

        if (!$mapping) {
            throw new \Exception("Mapping tidak ditemukan untuk letter ID $letterId dan part $partName.");
        }

        $letterUpload = LetterUpload::where('id', $mapping->letterable_id)
            ->latest('version')
            ->first();

        if (!$letterUpload) {
            throw new \Exception("LetterUpload tidak ditemukan.");
        }

        $letterUpload->update([
            'needs_revision' => true,
            'revision_note' => $note,
            // 'version' => DB::raw('version + 1'),`
        ]);
    }

    public function checkRevisionInputForRepliedStatus()
    {
        if (in_array($this->status, ['replied', 'rejected'])) {
            foreach ($this->revisionParts as $partName) {
                $note = $this->revisionNotes[$partName] ?? null;

                $this->updateRevisionFromMapping($this->letterId, $partName, $note);
            }
        }
    }

    public function save()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                $this->checkRevisionInputForRepliedStatus();

                $letter = Letter::findOrFail($this->letterId);

                $letter->transitionToStatus($this->getStatusFromInput(), ($this->notes) ? $this->notes : null);

                $user = User::findOrFail($letter->user_id);
                Notification::send($user, new NewServiceRequestNotification($letter, auth()->user()));

                $this->reset(['status', 'notes']);
                $this->closeModal();

                return redirect()->to("/letter/$this->letterId")
                    ->with('status', [
                        'variant' => 'success',
                        'message' => 'Create direct Letter successfully!'
                    ]);
            });
        } catch (ModelNotFoundException $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Letter not found',
            ]);
            return;
        }
    }
}
