<?php

namespace App\Livewire\Letters;

use App\Enums\Division;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;

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

    public $selectedOption = '';

    public $meeting = [];

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
                Rule::in('approved_kasatpel', 'replied', 'rejected')
            ];

            $rules['revisionParts'] = [
                'required',
                'array',
                'min:1'
            ];

            foreach ($this->revisionParts as $index => $partNumber) {
                $rules["revisionNotes.$partNumber"] = ['required', 'string'];
            }
        }

        if ($this->status === 'replied_kapusdatin') {
            $rules['status'] = [
                Rule::in('approved_kapusdatin', 'replied_kapusdatin')
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

    private function getSiDataRequests()
    {
        return Letter::with('documentUploads')->findOrFail($this->letterId);
    }

    public function saveDisposition()
    {
        $this->validate();

        $this->authorize('can disposition', $this->letterId);

        DB::transaction(function () {
            // Mencari object letter yang sesuai
            $letter = Letter::findOrFail($this->letterId);

            // Transisi status
            $letter->transitionStatusFromPending(
                $this->status,
                $this->getSelectedDivisionId(),
            );

            $letter->refresh();

            $letter->logStatus($this->notes);

            DB::afterCommit(function () use ($letter) {
                $letter->sendDispositionServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $letter->status->toastMessage(),
            ]);

            return $this->redirect("/letter/$this->letterId", true);
        });
    }

    public function updatedStatus($value)
    {
        if ($value !== 'rejected') {
            $this->notes = '';
        }
    }

    public function save()
    {
        $this->validate();

        // $this->authorize('canPerformStep1Verification', $this->getSiDataRequests());

        DB::transaction(function () {
            $siRequest = $this->getSiDataRequests();

            $this->checkRevisionInputForRepliedStatus($siRequest);

            $siRequest->transitionStatusFromProcess($this->status);

            $siRequest->refresh();

            $siRequest->logStatus($this->notes);

            DB::afterCommit(function () use ($siRequest) {
                $siRequest->sendProcessServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $siRequest->status->toastMessage(),
            ]);

            return $this->redirect("/letter/$this->letterId", true);
        });
    }

    public function createMeeting()
    {
        $rules = [
            'selectedOption' => 'required|in:in-person,online-meet',
            'meeting.date' => 'required|date',
            'meeting.start' => 'required|date_format:H:i',
            'meeting.end' => 'required|date_format:H:i|after:meeting.start',
        ];

        if ($this->selectedOption === 'in-person') {
            $rules['meeting.location'] = 'required|string|max:255';
        } elseif ($this->selectedOption === 'online-meet') {
            $rules['meeting.link'] = 'required|url|max:255';
        }

        $this->validate($rules);

        $siRequest = $this->getSiDataRequests();

        DB::transaction(function () use ($siRequest) {
            $siRequest->update([
                'meeting' => $this->meeting,
            ]);

            $siRequest->logStatusCustom('Rencana pertemuan telah dibuat, silahkan cek detailnya.');

            // DB::afterCommit(function () use ($siRequest) {
            //     $siRequest->sendMeetingServiceRequestNotification();
            // });

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Meeting berhasil dibuat',
            ]);

            return $this->redirect("/letter/$this->letterId", true);
        });
    }

    public function getSelectedDivisionId()
    {
        return Division::getIdFromString($this->selectedDivision);
    }

    public function checkRevisionInputForRepliedStatus($siRequest)
    {
        if (in_array($this->status, ['replied', 'replied_kapusdatin'])) {
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
