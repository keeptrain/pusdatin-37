<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class DetailHistory extends Component
{
    use WithFileUploads;

    #[Locked]
    public $letterId;

    public $letter;

    public $additionalFile;

    public function mount($id)
    {
        $this->letterId = $id;
        $this->letter = $this->letters();
    }

    public function letters()
    {
        return Letter::with('requestStatusTrack', 'documentUploads', 'mapping')->findOrFail($this->letterId);
    }

    #[Computed]
    public function activities()
    {
        return collect($this->letter->requestStatusTrack)
            ->sortByDesc('created_at')
            ->groupBy([
                fn($item) => $item->created_at->format('Y-m-d'),
                fn($item) => $item->created_at->format('H:i:s')
            ]);
    }

    #[Computed]
    public function activeVersion()
    {
        return $this->letter->documentUploads;
    }

    #[Computed]
    public function uploadedFile()
    {
        return collect($this->letter->documentUploads)->map(function ($documentUpload) {
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
            'additionalFile' => ['required','mimes:pdf']
        ], [
            'additionalFile.required' => 'File harus disisipkan!'
        ]);

        DB::transaction(function () {
            $hasPartNumber3 = $this->letter->documentUploads->contains(function ($documentUpload) {
                return $documentUpload->part_number == 3;
            });

            if (!$hasPartNumber3) { 
                if (!$this->letter->mapping) {
                    $this->letter->mapping()->create([
                        'letter_id' => $this->letter->id
                    ]);
                }

                $documentUpload = $this->letter->documentUploads()->create([
                    'part_number' => 3,
                    'need_revision' => true,
                ]);

                if (!empty($this->additionalFile)) { 
                    $path = $this->additionalFile->store('documents', 'public');
                    $this->additionalFile->store('documents', 'public');
                    $versioning = $documentUpload->versions()->create([
                        'file_path' => $path,
                        'is_resolved' => false,
                    ]);

                    $documentUpload->update([
                        'document_upload_version_id' => $versioning->id
                    ]);
                }
            }

            return $this->redirect("$this->letterId", true);
        });
    }

    public function downloadFile($typeNumber)
    {
        $template = $this->letter->documentUploads->where('part_number', $typeNumber)->first();

        if ($template) {
            $filePath = $template->activeVersion->file_path;

            $fileDownload = Storage::disk('public')->path($filePath);

            return response()->download($fileDownload);
        }

        abort(404, 'Template not found.');
    }
}
