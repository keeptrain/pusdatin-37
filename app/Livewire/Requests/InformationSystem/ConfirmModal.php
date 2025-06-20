<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Enums\Division;
use Livewire\Component;
use App\Models\InformationSystemRequest;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;

class ConfirmModal extends Component
{
    #[Locked]
    public int $systemRequestId;

    public $allowedParts;

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
                Rule::in('completed', 'process', 'approved_kapusdatin', 'approved_kasatpel', 'replied', 'rejected', 'disposition')
            ],
        ];

        if ($this->status === 'disposition') {
            $rules['notes'] = [
                'required'
            ];
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
        }

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
            'revisionParts.required' => 'Butuh bagian yang harus di revisi',
        ];
    }

    public function mount(int $systemRequestId, $allowedParts)
    {
        $this->systemRequestId = $systemRequestId;
        $this->allowedParts = $allowedParts;
    }

    private function getInformationSystemRequest()
    {
        return InformationSystemRequest::with('documentUploads')->findOrFail($this->systemRequestId);
    }

    public function saveDisposition()
    {
        $this->validate();

        $this->authorize('can disposition', $this->systemRequestId);

        DB::transaction(function () {
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);

            // Transisi status
            $systemRequest->transitionStatusFromPending(
                $this->status,
                $this->getSelectedDivisionId(),
                $this->notes
            );

            $systemRequest->refresh();

            $systemRequest->logStatus(null);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendDispositionServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);

            $this->redirectRoute('is.show', $systemRequest->id, navigate: true);
        });
    }

    public function updatedStatus($value)
    {
        if ($value !== 'rejected') {
            // $this->notesHistorie = '';
        }
    }

    public function save()
    {
        $this->validate();

        // $this->authorize('canPerformStep1Verification', $this->getInformationSystemRequest());

        DB::transaction(function () {
            $systemRequest = $this->getInformationSystemRequest();

            $this->checkRevisionInputForRepliedStatus($systemRequest);

            $systemRequest->transitionStatusFromProcess($this->status);

            $systemRequest->refresh();

            $systemRequest->logStatus($this->notes);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendProcessServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);

            $this->redirectRoute('is.show', $systemRequest->id, navigate: true);
        });
    }

    public function getSelectedDivisionId()
    {
        return Division::getIdFromString($this->selectedDivision);
    }

    public function checkRevisionInputForRepliedStatus($systemRequest)
    {
        if (in_array($this->status, ['replied', 'replied_kapusdatin'])) {
            $documentUploadsByPartNumber = $systemRequest->documentUploads->keyBy('part_number');

            foreach ($this->revisionParts as $partNumber) {
                if (isset($this->revisionNotes[$partNumber])) {
                    $revisionNote = $this->revisionNotes[$partNumber];

                    $documentUpload = $documentUploadsByPartNumber->get($partNumber);

                    if ($documentUpload) {
                        $documentUpload->createRevision($revisionNote);
                    }
                }
            }
        }
    }

    public function processPusdatin()
    {
        DB::transaction(function () {
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);

            $systemRequest->status->transitionTo(\App\States\InformationSystem\Process::class);

            $systemRequest->refresh();

            $systemRequest->logStatus(null);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendProcessServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);

            $this->redirectRoute('is.show', $systemRequest->id, navigate: true);
        });
    }

    public function completed()
    {
        DB::transaction(function () {
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);

            $systemRequest->status->transitionTo(\App\States\InformationSystem\Completed::class);

            $systemRequest->refresh();

            $systemRequest->logStatus(null);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendProcessServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);

            $this->redirectRoute('is.show', $systemRequest->id, navigate: true);
        });
    }
}
