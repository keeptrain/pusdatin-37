<?php

namespace App\Livewire\Requests\PublicRelation;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\PromkesQueue;

class ConfirmModal extends Component
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

            $this->redirect("$prRequest->id", true);
        });
    }

    public function curation()
    {
        $prRequest = PublicRelationRequest::findOrFail($this->publicRelationId);

        $dynamicRules = [];

        foreach ($this->publicRelationRequest->documentUploads as $documentUploadItem) {
            $dynamicRules["curationFileUpload.{$documentUploadItem->part_number}"] = ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx'];
        }

        $this->validate(
            $dynamicRules,
            ['curationFileUpload.required' => 'File kurasi di perlukan']
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

            $this->redirect("$prRequest->id", true);
        });
    }

    public function process()
    {
        $prRequest = PublicRelationRequest::findOrFail($this->publicRelationId);

        $this->authorize('dispositionToPr', $prRequest);

        DB::transaction(function () use ($prRequest) {
            $prRequest->transitionStatusToPusdatinProcess();

            $prRequest->refresh();

            $prRequest->logStatus(null);

            DB::afterCommit(function () use ($prRequest) {
                $prRequest->sendPrRequestNotification();
            });

            $this->redirect("$prRequest->id", true);
        });
    }

    public function completed()
    {
        $prRequest = PublicRelationRequest::findOrFail($this->publicRelationId);

        $dynamicRules = [];

        foreach ($this->publicRelationRequest->documentUploads as $documentUpload) {
            $dynamicRules["mediaLinks.{$documentUpload->part_number}"] = ['required', 'url'];
        }

        $this->validate($dynamicRules, [
            'mediaLinks.1.required' => 'Diperlukan'
        ]);

        $this->authorize('completedPrProcess', $prRequest);

        DB::transaction(function () use ($prRequest) {
            $prRequest->transitionStatusToCompleted($this->mediaLinks);

            $prRequest->refresh();

            $prRequest->logStatus(null);

            DB::afterCommit(function () use ($prRequest) {
                $prRequest->sendPrRequestNotification();
            });

            $this->redirect("$prRequest->id", true);
        });
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
                    'file_path'      => $filePath,
                    'version'        => $nextVersionNumber,
                    'is_resolved'    => true,
                    'revision_note'  => 'Versi terbaru setelah di kurasi oleh Promkes. Versi Pemohon adalah ' . $currentMaxVersionNumber,
                ]);

                $targetDocumentUpload->update([
                    'document_upload_version_id' => $newVersion->id,
                ]);
            }
        }
    }

    public function linksToArray()
    {
        return [
            'media_type' => '',
            'link' => ''
        ];
    }
}
