<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use Livewire\Attributes\On;
use App\States\LetterStatus;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModalConfirmation extends Component
{
    public $showModal = false;

    public int $letterId;

    public Letter $letter;

    public LetterStatus $letterStatus;

    public $status = '';

    public $notes = '';

    public bool $active_revision;

    public $tracks;

    public $revisionParts = [];
    public $revisionNotes = [];

    public $revisionNotesPerPart = [];

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
            if ($this->active_revision) {
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

    public function render()
    {
        return view('livewire.letters.modal-confirmation');
    }

    public function mount() {}

    #[On('modal-confirmation')]
    public function openModal(int $id)
    {
        $this->showModal = true;
        $this->letterId = $id;
        $this->letter = Letter::with('uploads')->findOrFail($this->letterId);
        $this->active_revision = $this->letter->active_revision;
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
    private function getCachedLetter()
    {
        return Cache::remember('letter_' . $this->letterId, 300, function () {
            return Letter::with('status')->findOrFail($this->letterId);
        });
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

    public function save()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                if (in_array($this->status, ['replied', 'rejected'])) {
                    foreach ($this->revisionParts as $partName) {
                        $note = $this->revisionNotes[$partName] ?? null;

                        LetterUpload::where('letter_id', $this->letterId)
                            ->where('part_name', $partName)
                            ->latest('version')
                            ->first()?->update([
                                'needs_revision' => true,
                                'revision_note' => $note,
                                'version' => DB::raw('version + 1'),
                            ]);
                    }
                }

                $this->letter->transitionToStatus($this->getStatusFromInput(), $this->notes);
                // $this->letter->uploads;
                $this->reset(['status', 'notes']);
                $this->closeModal();
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
