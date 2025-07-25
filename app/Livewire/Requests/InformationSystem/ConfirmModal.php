<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Enums\Division;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Enums\InformationSystemRequestPart;
use Illuminate\Support\Facades\Cache;
use App\Models\InformationSystemRequest;

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

    public bool $hasPartNumber5 = false;

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
        });

        $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
    }

    public function updatedStatus($value)
    {
        if ($value !== 'rejected') {
            // $this->notesHistorie = '';
        }
    }

    public function formatRevisionNotes()
    {
        $revisionNotes = $this->revisionNotes;
        $formatted = [];

        foreach ($revisionNotes as $key => $value) {
            $label = InformationSystemRequestPart::tryFrom($key)->label();

            $formatted[$label] = $value;
        }

        return $formatted;
    }

    public function save()
    {
        $this->validate();

        // $this->authorize('canPerformStep1Verification', $this->getInformationSystemRequest());

        DB::transaction(function () {
            $systemRequest = $this->getInformationSystemRequest();

            $this->checkRevisionInputForRepliedStatus($systemRequest);

            $systemRequest->transitionStatusFromDisposition($this->status);

            $systemRequest->refresh();

            $systemRequest->logStatus($this->notes);

            DB::afterCommit(function () use ($systemRequest) {
                $data = [
                    'title' => $systemRequest->title,
                    'revision_notes' => $this->formatRevisionNotes(),
                    'url' => route('is.edit', $systemRequest->id),
                ];

                Cache::rememberForever("revision-mail-{$systemRequest->id}", fn() => $data);

                $systemRequest->sendProcessServiceRequestNotification($data);
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);
        });

        $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
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
        });

        $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
    }

    public function completed()
    {
        try {
            DB::transaction(function () {
                $systemRequest = InformationSystemRequest::with([
                    'documentUploads' => function ($query) {
                        $query->select('document_upload_version_id', 'documentable_id', 'part_number');
                    }
                ])->findOrFail($this->systemRequestId);

                // Check part number 5
                $hasPartNumber5 = $systemRequest->documentUploads()->where('part_number', InformationSystemRequestPart::NON_DISCLOSURE_AGREEMENT->value)->exists();

                if ($hasPartNumber5) {
                    // Add error to error bag
                    $this->addError('hasPartNumber5', InformationSystemRequestPart::NON_DISCLOSURE_AGREEMENT->label() . ' belum diunggah oleh pemohon');
                    return;
                }

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
            });
        } catch (\Exception $e) {
            // Tangani exception jika terjadi error dalam transaksi
            $this->addError('error', 'An error occurred: ' . $e->getMessage());
            return;
        }

        // Redirect hanya jika tidak ada error
        if (!$this->getErrorBag()->has('hasPartNumber5')) {
            $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
        }
    }
}
