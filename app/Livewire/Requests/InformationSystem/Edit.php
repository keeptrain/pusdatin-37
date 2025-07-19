<?php

namespace App\Livewire\Requests\InformationSystem;

use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\Documents\DocumentUpload;
use App\Models\Documents\UploadVersion;
use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\Cache;

#[Title('Revisi Permohonan Layanan')]
class Edit extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $systemRequestId;
    public $systemRequest;

    public string $title;
    public string $reference_number;
    public array $revisedFiles = [];
    public string $notes = '';

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
            // Get available information system request instance
            $systemRequest = $this->systemRequest;

            // Initialize empty arrays
            // Array of part numbers that need revision
            $revisedParts = [];

            // Array of paths to update in revisions
            $pathsToUpdateInRevisions = [];

            // Array of document upload IDs to mark not needing revision
            $documentUploadIdsToMarkNotNeedingRevision = [];

            // Looping through revised files for each part number
            foreach ($this->revisedFiles as $partNumber => $file) {
                // Get the document upload and check if part number need revision
                $documentUpload = $systemRequest->documentUploads->first(function ($doc) use ($partNumber) {
                    return $doc->part_number == $partNumber && $doc->need_revision;
                });

                // If document upload not found, continue to next iteration
                if (!$documentUpload) {
                    continue;
                }

                // Store the revision file to public disk
                $path = $file->store('documents', 'public');

                $documentUpload->load('versions');

                // Get the latest revision that not resolved and not have file path
                $revision = $documentUpload->versions
                    ->where('is_resolved', false)
                    ->whereNull('file_path')
                    ->sortByDesc('id')
                    ->first();

                // If revision not found, continue to next iteration
                if (!$revision) {
                    continue;
                }

                // Add to paths to update
                $pathsToUpdateInRevisions[$revision->id] = $path;

                // Add to document upload IDs to mark not needing revision
                $documentUploadIdsToMarkNotNeedingRevision[] = $documentUpload->id;

                // Add to revised parts
                $revisedParts[] = $documentUpload->part_number_label;
            }

            // Update document upload need revision status
            if (!empty($documentUploadIdsToMarkNotNeedingRevision)) {
                DocumentUpload::whereIn('id', array_unique($documentUploadIdsToMarkNotNeedingRevision))
                    ->update(['need_revision' => false]);
            }

            // Update revision file path
            if (!empty($pathsToUpdateInRevisions)) {
                foreach ($pathsToUpdateInRevisions as $revisionId => $filePath) {
                    UploadVersion::where('id', $revisionId)->update(['file_path' => $filePath]);
                }
            }

            // Update request status to need review
            $systemRequest->updatedForNeedReview();

            $systemRequest->refresh();

            $systemRequest->logStatusRevision($this->notes, $revisedParts);

            DB::afterCommit(function () use ($systemRequest) {
                $systemRequest->sendProcessServiceRequestNotification();

                Cache::forget("revision-mail-{$this->systemRequestId}");

                session()->flash('status', [
                    'variant' => 'success',
                    'message' => $systemRequest->status->toastMessage(),
                ]);
            });
        });

        $this->redirectRoute('detail.request', ['type' => 'information-system', $this->systemRequestId]);
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
