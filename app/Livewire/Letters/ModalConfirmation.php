<?php

namespace App\Livewire\Letters;

use App\Models\User;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

class ModalConfirmation extends Component
{
    #[Locked]
    public int $letterId;

    public $availablePart;

    public $status = '';

    public $notes = '';

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

        if ($this->status === 'disposition') {
            $rules['selectedDivision'] = [
                'required',
            ];
        }

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
            'status.required' => 'Status tidak boleh kosong',
            'selectedDivision.required' => 'Tujuan divisi tidak boleh kosong',
            'revisionParts.required' => 'Butuh bagian yang harus di revisi'
        ];
    }

    public function mount(int $letterId, $availablePart)
    {
        $this->letterId = $letterId;
        $this->availablePart = $availablePart;
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
                $siRequest = Letter::with('documentUploads')->findOrFail($this->letterId);

                $this->checkRevisionInputForRepliedStatus($siRequest);

                $siRequest->transitionStatusFromProcess($this->status, $siRequest->current_division, ($this->notes) ? $this->notes : null);

                $this->reset(['status', 'notes']);

                return redirect()->to("/letter/$this->letterId")
                    ->with('status', [
                        'variant' => 'success',
                        'message' => $siRequest->status->toastMessage(),
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

    public function checkRevisionInputForRepliedStatus($siRequest)
    {
        if (in_array($this->status, ['replied', 'rejected'])) {
            $documentUploadsByPartNumber = $siRequest->documentUploads->keyBy('part_number');

            foreach ($this->revisionParts as $partNumber) {
                if (isset($this->revisionNotes[$partNumber])) {
                    $revisionNote = $this->revisionNotes[$partNumber];

                    $documentUpload = $documentUploadsByPartNumber->get($partNumber);

                    if ($documentUpload) {
                        // Lanjutkan dengan membuat catatan version
                        $latestRevision = $documentUpload->versions()->latest('version')->first();
                        $nextVersion = $latestRevision ? $latestRevision->version + 1 : 1;

                        $documentUpload->versions()->create([
                            'version' => $nextVersion,
                            'revision_note' => $revisionNote
                        ]);

                        $documentUpload->update(['need_revision' => true]);
                    } 
                } 
            }
        }
    }

 
}
