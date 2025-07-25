<?php

namespace App\Livewire\Requests\PublicRelation;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\PromkesQueue;
use App\Enums\PublicRelationRequestPart;

class ActionsModal extends Component
{
    use WithFileUploads;

    #[Locked]
    public $publicRelationId;

    public array $curationFileUpload = [];

    public array $mediaLinks = [];

    public $publicRelationRequest;

    public function queuePromkes()
    {
        $prRequest = $this->publicRelationRequest;

        $this->authorize('queuePromkes', $prRequest);

        DB::transaction(function () use ($prRequest) {
            $prRequest->status->transitionTo(PromkesQueue::class);

            $prRequest->logStatus(null);

            session()->flash('status', [
                'variant' => 'success',
                'message' => $prRequest->status->toastMessage(),
            ]);
        });

        $this->redirectRoute('pr.show', $prRequest->id, navigate: true);
    }

    #[Computed]
    public function getAllowedDocument()
    {
        return $this->publicRelationRequest
            ->documentUploads
            ->filter(function ($document) {
                return $document->part_number !== 0;
            })
            ->values();
    }

    public function curation()
    {
        $prRequest = PublicRelationRequest::findOrFail($this->publicRelationId);

        $dynamicRules = [];

        foreach ($this->getAllowedDocument() as $documentUploadItem) {
            $dynamicRules["curationFileUpload.{$documentUploadItem->part_number}"] = ['required'];
        }

        $this->validate(
            $dynamicRules,
            [
                'curationFileUpload.required' => 'File kurasi di perlukan',
                'curationFileUpload.1' => 'File kurasi audio di perlukan',
                'curationFileUpload.2' => 'File kurasi infografis di perlukan',
                'curationFileUpload.3' => 'File kurasi poster di perlukan',
                'curationFileUpload.4' => 'File kurasi media di perlukan',
                'curationFileUpload.5' => 'File kurasi bumper di perlukan',
                'curationFileUpload.6' => 'File kurasi backdrop kegiatan di perlukan',
                'curationFileUpload.7' => 'File kurasi spanduk di perlukan',
                'curationFileUpload.8' => 'File kurasi roll banner di perlukan',
                'curationFileUpload.9' => 'File kurasi sertifikat di perlukan',
                'curationFileUpload.10' => 'File kurasi press release di perlukan',
                'curationFileUpload.11' => 'File kurasi artikel di perlukan',
                'curationFileUpload.12' => 'File kurasi peliputan di perlukan',
            ]
        );

        $this->authorize('curationPromkes', $prRequest);

        DB::transaction(function () use ($prRequest) {
            $this->checkCurationFileUpload($prRequest);

            $prRequest->transitionStatusToPromkesComplete();

            $prRequest->refresh();

            $prRequest->logStatus(null);

            DB::afterCommit(function () use ($prRequest) {
                $prRequest->sendPrRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $prRequest->status->toastMessage(),
            ]);
        });

        $this->redirectRoute('pr.show', $prRequest->id, navigate: true);
    }

    public function queuePusdatin()
    {
        $prRequest = PublicRelationRequest::findOrFail($this->publicRelationId);

        $this->authorize('queuePusdatin', $prRequest);

        DB::transaction(function () use ($prRequest) {
            $prRequest->transitionStatusToPusdatinQueue();

            $prRequest->refresh();

            $prRequest->logStatus(null);

            DB::afterCommit(function () use ($prRequest) {
                $prRequest->sendPrRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $prRequest->status->toastMessage(),
            ]);
        });

        $this->redirectRoute('pr.show', $prRequest->id, navigate: true);
    }

    public function processPusdatin()
    {
        $prRequest = PublicRelationRequest::findOrFail($this->publicRelationId);

        $this->authorize('processPusdatin', $prRequest);

        DB::transaction(function () use ($prRequest) {
            $prRequest->transitionStatusToPusdatinProcess();

            $prRequest->refresh();

            $prRequest->logStatus(null);

            DB::afterCommit(function () use ($prRequest) {
                $prRequest->sendPrRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $prRequest->status->toastMessage(),
            ]);
        });

        $this->redirectRoute('pr.show', $this->publicRelationId, navigate: true);
    }

    #[Computed]
    public function linkProductions()
    {
        return collect($this->mediaLinks)->map(function ($url, $key) {
            $label = PublicRelationRequestPart::tryFrom((int) $key)?->label() ?? 'Unknown';

            return [
                'label' => $label,
                'url' => $url,
            ];
        })->values();
    }

    public function completed()
    {
        $prRequest = PublicRelationRequest::findOrFail($this->publicRelationId);

        $dynamicRules = [];

        foreach ($this->getAllowedDocument() as $documentUpload) {
            $dynamicRules["mediaLinks.{$documentUpload->part_number}"] = ['required', 'url'];
        }

        $this->validate(
            $dynamicRules,
            [
                'mediaLinks.1' => 'Link audio di perlukan',
                'mediaLinks.2' => 'Link infografis di perlukan',
                'mediaLinks.3' => 'Link poster di perlukan',
                'mediaLinks.4' => 'Link media di perlukan',
                'mediaLinks.5' => 'Link bumper di perlukan',
                'mediaLinks.6' => 'Link backdrop kegiatan di perlukan',
                'mediaLinks.7' => 'Link spanduk di perlukan',
                'mediaLinks.8' => 'Link roll banner di perlukan',
                'mediaLinks.9' => 'Link sertifikat di perlukan',
                'mediaLinks.10' => 'Link press release di perlukan',
                'mediaLinks.11' => 'Link artikel di perlukan',
                'mediaLinks.12' => 'Link peliputan di perlukan',
            ]
        );

        $this->authorize('completedRequest', $prRequest);

        DB::transaction(function () use ($prRequest) {
            $prRequest->transitionStatusToCompleted($this->mediaLinks);

            $prRequest->refresh();

            $prRequest->logStatus(null);

            DB::afterCommit(function () use ($prRequest) {
                $prRequest->sendPrRequestNotification($this->formatForNotificationMail($prRequest));
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $prRequest->status->toastMessage(),
            ]);
        });

        $this->redirectRoute('pr.show', $this->publicRelationId, navigate: true);
    }

    private function checkCurationFileUpload($prRequest)
    {
        foreach ($this->curationFileUpload as $partNumber => $uploadedFile) {
            if ($uploadedFile) {
                $filePath = $uploadedFile->store('documents', 'public');

                $targetDocumentUpload = $prRequest->documentUploads->firstWhere('part_number', $partNumber);

                if (!$targetDocumentUpload) {
                    session()->flash('error', "Gagal mengunggah beberapa file. Tidak ada dokumen untuk: {$partNumber}");
                    continue;
                }

                $targetDocumentUpload->load('versions');

                $currentMaxVersionNumber = $targetDocumentUpload->versions->max('version') ?? 0;
                $nextVersionNumber = $currentMaxVersionNumber + 1;

                $newVersion = $targetDocumentUpload->versions()->create([
                    'file_path' => $filePath,
                    'version' => $nextVersionNumber,
                    'is_resolved' => true,
                    'revision_note' => 'Versi terbaru setelah di kurasi oleh Promkes. Versi Pemohon adalah ' . $currentMaxVersionNumber,
                ]);

                $targetDocumentUpload->update([
                    'document_upload_version_id' => $newVersion->id,
                ]);
            }
        }
    }

    public function formatForNotificationMail($prRequest)
    {
        $mediaLinks = $this->mediaLinks;
        $targets = $prRequest->target;
        $formatted = [];

        foreach ($mediaLinks as $key => $value) {
            $label = PublicRelationRequestPart::tryFrom($key)->label();

            $formatted[$label] = $value;
        }

        return [
            'theme' => $prRequest->theme,
            'target' => implode(',', $targets),
            'completed_date' => $prRequest->completed_date,
            'media' => $formatted,
            'rating_link' => route('detail.request', ['type' => 'public_relation', 'id' => $prRequest->id]),
        ];
    }
}
