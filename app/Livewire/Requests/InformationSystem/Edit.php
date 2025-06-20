<?php

namespace App\Livewire\Requests\InformationSystem;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\Documents\DocumentUpload;
use App\Models\Documents\UploadVersion;
use App\Models\InformationSystemRequest;

class Edit extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $systemRequestId;

    public $systemRequest;

    public $title;
    public $reference_number;

    public $revisedFiles = [];

    public $notes = '';

    public function rules()
    {
        $rules = [
            'title' => 'required|string',
            'reference_number' => 'required|string'
        ];

        $revisedParts = $this->systemRequestNeedRevision();

        foreach ($revisedParts as $partNumber) {
            $rules["revisedFiles.$partNumber"] = 'required|file|mimes:pdf|max:1048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'reference_number.required' => 'Reference number is required',
            'revisedFiles.1.required' => 'Dokumen Identifikasi kebutuhan Pembangunan dan Pengembangan Aplikasi SPBE harus ada',
            'revisedFiles.2.required' => 'SOP Aplikasi SPBE harus ada',
            'revisedFiles.3.required' => 'Pakta Integritas Pemanfaatan Aplikasi harus ada',
            'revisedFiles.4.required' => 'Form RFC Pusdatinkes harus ada',
            'revisedFiles.5.required' => 'NDA Pusdatin Dinkes harus ada',
        ];
    }

    public function mount(int $id)
    {
        $this->systemRequestId = $id;
        $this->systemRequest = InformationSystemRequest::with([
            'documentUploads.versions',
        ])->findOrFail($id);

        $this->fill(
            $this->systemRequest->only('title', 'reference_number')
        );
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $systemRequest = $this->systemRequest;

            $revisedParts = [];
            $pathsToUpdateInRevisions = [];
            $documentUploadIdsToMarkNotNeedingRevision = [];

            foreach ($this->revisedFiles as $partNumber => $file) {
                $documentUpload = $systemRequest->documentUploads->first(function ($doc) use ($partNumber) {
                    return $doc->part_number == $partNumber && $doc->need_revision;
                });

                if (!$documentUpload) {
                    continue;
                }

                $path = $file->store('documents', 'public');

                $documentUpload->load('versions');

                $revision = $documentUpload->versions
                    ->where('is_resolved', false)
                    ->whereNull('file_path')
                    ->sortByDesc('id')
                    ->first();

                if (!$revision) {
                    continue;
                }

                // Kumpulkan untuk update
                $pathsToUpdateInRevisions[$revision->id] = $path;
                $documentUploadIdsToMarkNotNeedingRevision[] = $documentUpload->id;

                $revisedParts[] = $documentUpload->part_number_label;
            }

            if (!empty($documentUploadIdsToMarkNotNeedingRevision)) {
                DocumentUpload::whereIn('id', array_unique($documentUploadIdsToMarkNotNeedingRevision))
                    ->update(['need_revision' => false]);
            }

            if (!empty($pathsToUpdateInRevisions)) {
                foreach ($pathsToUpdateInRevisions as $revisionId => $filePath) {
                    UploadVersion::where('id', $revisionId)->update(['file_path' => $filePath]);
                }
            }

            $systemRequest->updatedForNeedReview();

            $systemRequest->refresh();

            $systemRequest->logStatusRevision($this->notes, $revisedParts);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendProcessServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $systemRequest->status->toastMessage(),
            ]);

            return $this->redirect("/history/information-system/{$this->systemRequestId}", true);
        });
    }

    #[Computed]
    public function checkNotResolvedDocuments()
    {
        return $this->systemRequest->documentUploads->versions->where('is_resolved', false);
    }

    #[Computed]
    public function checkDocumentUploadNeedRevision()
    {
        return $this->systemRequest->documentUploads->contains(function ($documentUpload) {
            return $documentUpload->need_revision;
        });
    }

    public function systemRequestNeedRevision()
    {
        return collect($this->systemRequest->documentUploads)
            ->filter(fn($map) => $map->need_revision)
            ->map(fn($map) => $map->part_number)
            ->values();
    }
}
