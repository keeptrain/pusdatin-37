<?php

namespace App\Livewire\Requests\PublicRelation;

use App\Models\User;
use App\States\PublicRelation\Completed;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PusdatinProcess;
use Livewire\WithFileUploads;

class ConfirmModal extends Component
{
    use WithFileUploads;

    public array $curationFileUpload = [];

    public array $mediaLinks = [];

    public $documentUploads;

    public function curation()
    {
        $prRequest = $this->documentUploads;

        $user = User::role('head_verifier')->first();

        $dynamicRules = [];

        foreach ($prRequest->documentUploads as $documentUploadItem) {
            $dynamicRules["curationFileUpload.{$documentUploadItem->part_number}"] = ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx'];
        }

        $this->validate($dynamicRules);

        DB::transaction(function () use ($prRequest, $user) { 
            $prRequest->update([
                'status' => $prRequest->status->transitionTo(PromkesComplete::class),
                'active_review' => $user->id,
            ]);
          
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
        });
    }

    public function process()
    {
        $prRequest = $this->documentUploads;
        $user = User::role('pr_verifier')->first();

        DB::transaction(function () use ($prRequest, $user) {
            $prRequest->update([
                'status' => $prRequest->status->transitionTo(PusdatinProcess::class),
                'active_review' => $user->id,
            ]);

            $prRequest->requestStatusTrack()->create([
                'action' => $prRequest->status->trackingActivity(null),
                'created_by' => auth()->user()->name,
            ]);

            // Notification::send($user, new NewServiceRequestNotification());

        });
    }

    public function completed()
    {
        $prRequest = $this->documentUploads;

        $dynamicRules = [];

        foreach ($this->documentUploads->documentUploads as $documentUpload) {
            $dynamicRules["mediaLinks.{$documentUpload->part_number}"] = ['required', 'url'];
        }

        $this->validate($dynamicRules);

        DB::transaction(function () use ($prRequest) {
            $prRequest->update([
                'status' => $prRequest->status->transitionTo(Completed::class),
                'links' => $this->mediaLinks
            ]);

            $prRequest->requestStatusTrack()->create([
                'action' => $prRequest->status->trackingActivity(null),
                'created_by' => auth()->user()->name,
            ]);

            // Notification::send($user, new NewServiceRequestNotification());

        });
    }

    public function linksToArray()
    {
        return [
            'media_type' => '',
            'link' => ''
        ];
    }
}
