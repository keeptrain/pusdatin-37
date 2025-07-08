<?php

namespace App\Livewire\Requests\PublicRelation;

use App\Models\PublicRelationRequest;
use App\Services\FileUploadServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class Rollback extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $prRequestId;

    public $publicRelationRequest;

    public array $mediaFiles;

    public array $links;

    public function rules(): array
    {
        return [
            'mediaFiles.*' => 'file|mimes:pdf',
            'links.*' => 'required|url',
        ];
    }

    public function mount(int $id): void
    {
        $this->prRequestId = $id;
        $this->publicRelationRequest = PublicRelationRequest::with(['documentUploads.activeVersion' => function ($query) {
            $query->select('id', 'document_upload_id', 'file_path');
        }])->findOrFail($id);
        $this->links = $this->publicRelationRequest->links ?? [];
    }

    public function render(): object
    {
        return view('livewire.requests.public-relation.rollback');
    }

    #[Computed]
    public function allowedDocuments(PublicRelationRequest $publicRelationRequest)
    {
        return $publicRelationRequest->documentUploads->filter(function ($document) {
            return $document->part_number !== 0;
        })->values();
    }

    #[Computed]
    public function availableMedia()
    {
        $links = $this->publicRelationRequest->links;
        $mediaFiles = $this->allowedDocuments($this->publicRelationRequest);

        return $mediaFiles->map(function ($mediaFile) use ($links) {
            return [
                'part_number' => $mediaFile->part_number,
                'part_number_label' => $mediaFile->part_number_label,
                'file_path' => $mediaFile->load('activeVersion')->activeVersion->file_path,
                'links' => $links[$mediaFile->part_number] ?? null,
            ];
        })->keyBy('part_number');
    }

    public function update(): void
    {
        $publicRelationRequest = PublicRelationRequest::with('documentUploads.activeVersion')->findOrFail($this->prRequestId);

        $this->validate();

        DB::transaction(function () use ($publicRelationRequest) {
            $this->updateLinks($publicRelationRequest);
            $this->updateMediaFiles($publicRelationRequest);
        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Data berhasil diupdate',
        ]);

        $this->redirectRoute('pr.show', $publicRelationRequest->id, navigate: true);
    }

    protected function updateLinks(PublicRelationRequest $publicRelationRequest): void
    {
        if (!empty($this->links)) {
            $publicRelationRequest->update([
                'links' => $this->links
            ]);
        }
    }

    protected function checkMediaFiles(FileUploadServices $uploadService): array
    {
        $mediaFiles = $this->mediaFiles;
        if (!empty($mediaFiles)) {
            return $uploadService->storeMultiplesFilesPr($mediaFiles);
        }

        return [];
    }

    protected function updateMediaFiles(PublicRelationRequest $publicRelationRequest): void
    {
        $mediaFiles = $this->checkMediaFiles(new FileUploadServices());

        collect($mediaFiles)->each(function ($file) use ($publicRelationRequest) {
            $documentUpload = $publicRelationRequest->documentUploads
                ->firstWhere('part_number', $file['part_number']);

            if ($documentUpload && $documentUpload->activeVersion) {
                // Store the old file path before updating
                $oldFilePath = $documentUpload->activeVersion->file_path;

                // Update the file path
                $documentUpload->activeVersion->update([
                    'file_path' => $file['file_path']
                ]);

                // Delete the old file if it exists
                if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }
        });
    }
}
