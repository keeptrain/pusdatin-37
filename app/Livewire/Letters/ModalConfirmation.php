<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use App\Models\letters\LettersMapping;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

class ModalConfirmation extends Component
{
    #[Locked]
    public int $letterId;

    public $status = '';

    public $notes = '';

    public bool $activeRevision;

    public $selectedDivision = '';

    public $revisionParts = [];

    public $revisionNotes = [];

    public function rules()
    {
        $rules = [
            'status' => [
                'required',
                'string',
                Rule::in('approved_kapusdatin', 'approved_kasatpel', 'replied', 'rejected', 'disposition')
            ],
        ];

        if ($this->status === 'process') {
            $rules['status'] = [
                Rule::in('process', 'rejected')
            ];
            $rules['selectedDivision'] = [
                'required',
                'string'
            ];
        };

        if ($this->status === 'replied') {
            $rules['status'] = [
                Rule::in('approved', 'replied', 'rejected')
            ];
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
            'selectedDivision.required' => 'Tujuan divisi tidak boleh kosong',
            'revisionParts.required' => 'Butuh bagian yang harus di revisi'
        ];
    }

    public function mount(int $letterId)
    {
        $this->letterId = $letterId;
    }

    public function saveDisposition()
    {
        $this->validate();

        DB::transaction(function () {
            $letter = Letter::findOrFail($this->letterId);

            $letter->transitionStatusFromDisposition(
                $this->status,
                $this->handleDivision($letter),
                ($this->notes) ? $this->notes : null
            );

            return redirect()->to("/letter/$this->letterId")
                ->with('status', [
                    'variant' => 'success',
                    'message' => $letter->status->toastMessage(),
                ]);
        });
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->checkRevisionInputForRepliedStatus();

                $letter = Letter::findOrFail($this->letterId);

                $letter->transitionStatusFromProcess($this->status, $letter->current_division, ($this->notes) ? $this->notes : null);

                $this->reset(['status', 'notes']);

                return redirect()->to("/letter/$this->letterId")
                    ->with('status', [
                        'variant' => 'success',
                        'message' => $letter->status->toastMessage(),
                    ]);
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function saveApproved()
    {
        $this->validate();

        DB::transaction(function () {
            $letter = Letter::findOrFail($this->letterId);

            $letter->transitionStatusFromApprovedKasatpel(
                $this->status,
                division: $letter->current_division
            );

            return redirect()->to("/letter/$this->letterId")
                ->with('status', [
                    'variant' => 'success',
                    'message' => $letter->status->toastMessage(),
                ]);
        });
    }

    public function handleDivision($letter)
    {
        if ($this->status == 'rejected') {
            return $this->handleNotification($letter->user_id, $letter);
        }

        $role = match ($this->selectedDivision) {
            'si' => 3,
            'data' => 4,
            default => null,
        };

        $user = User::role($role)->pluck('id')->first();

        $this->handleNotification($user, $letter);

        return $user;
    }

    public function handleNotification($recipientId, $letter)
    {
        $user = User::findOrFail($recipientId);

        Notification::send($user, new NewServiceRequestNotification($letter));
    }

    public function updateRevisionFromMapping(int $letterId, string $partNumber, string $note)
    {
        $mapping = LettersMapping::where('letter_id', $letterId)
            ->where('letterable_type', LetterUpload::class)
            ->whereHasMorph('letterable', [LetterUpload::class], function ($query) use ($partNumber) {
                $query->where('part_number', $partNumber);
            })
            ->first();

        if (!$mapping) {
            throw new \Exception("Mapping tidak ditemukan untuk letter ID $letterId dan part $partNumber.");
        }

        $letterUpload = LetterUpload::where('id', $mapping->letterable_id)
            ->first();

        if (!$letterUpload) {
            throw new \Exception("LetterUpload tidak ditemukan.");
        }

        $latestRevision = $letterUpload->version()->latest('version')->first();
        $nextVersion = $latestRevision ? $latestRevision->version + 1 : 1;

        if ($letterUpload->need_revision == false) {
            $revision = $letterUpload->version()->create([
                'document_upload_id' => $letterId,
                'version' => $nextVersion,
                'revision_note' => $note,
                'part_number' => $partNumber,
            ]);
        }

        if ($revision) {
            $letterUpload->update([
                'need_revision' => true,
            ]);
        }
    }

    public function checkRevisionInputForRepliedStatus()
    {
        if (in_array($this->status, ['replied', 'rejected'])) {
            foreach ($this->revisionParts as $partNumber) {
                $note = $this->revisionNotes[$partNumber] ?? null;


                $this->updateRevisionFromMapping($this->letterId, $partNumber, $note);
            }
        }
    }
}
