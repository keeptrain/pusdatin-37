<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class DetailHistory extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $id;

    public $type;

    public $additionalFile;

    public $content;

    public function mount($type, int $id)
    {
        $this->id = $id;
        $this->type = $type;
        $this->loadContent();
    }

    protected function loadContent()
    {
        $this->content = match ($this->type) {
            'information-system' => $this->letters(),
            'public-relation' => $this->prRequests(),
            default => abort(404, 'Invalid content type.')
        };
    }

    public function letters()
    {
        return Letter::with('documentUploads.activeVersion:id,file_path')->findOrFail($this->id);
    }

    public function prRequests()
    {
        return PublicRelationRequest::with('documentUploads.activeVersion:id,file_path')->findOrFail($this->id);
    }

    #[Computed]
    public function activities()
    {
        return $this->content->getGroupedRequestStatusTracks();
    }

    #[Computed]
    public function uploadedFile()
    {
        return collect($this->content->documentUploads)->map(function ($documentUpload) {
            return [
                'part_number' => $documentUpload->part_number,
                'part_number_label' => $documentUpload->part_number_label,
                'file_path' => $documentUpload->file_path,
            ];
        });
    }

    public function additionalUploadFile()
    {
        $this->validate([
            'additionalFile' => ['required', 'mimes:pdf']
        ], [
            'additionalFile.required' => 'File harus disisipkan!'
        ]);

        DB::transaction(function () {
            $hasPartNumber3 = $this->content->documentUploads->contains(function ($documentUpload) {
                return $documentUpload->part_number == 3;
            });

            if (!$hasPartNumber3) {
                $documentUpload = $this->content->documentUploads()->create([
                    'part_number' => 3,
                    'need_revision' => false,
                ]);

                if (!empty($this->additionalFile)) {
                    $path = $this->additionalFile->store('documents', 'public');
                    $this->additionalFile->store('documents', 'public');
                    $versioning = $documentUpload->versions()->create([
                        'file_path' => $path,
                        'is_resolved' => true,
                    ]);

                    $documentUpload->update([
                        'document_upload_version_id' => $versioning->id
                    ]);

                    $this->content->requestStatusTrack()->create([
                        'action' => 'Pemohon telah menambahkan dokumen pendukung',
                        'created_by' => auth()->user()->id
                    ]);
                }
            }

            return $this->redirect("$this->id", true);
        });
    }

    public function downloadFile($typeNumber)
    {
        $template = $this->content->documentUploads()->where('part_number', $typeNumber)->with('activeVersion')->first();

        if ($template) {
            $filePath = $template->activeVersion->file_path;

            $fileDownload = Storage::disk('public')->path($filePath);

            return response()->download($fileDownload);
        }

        abort(404, 'Template not found.');
    }
}
