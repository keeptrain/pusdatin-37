<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class Edit extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $letterId;

    public $letter;

    public $title;
    public $reference_number;
    public $body;

    public $revisedFiles = [];

    public $notes = '';

    public function rules()
    {
        $rules = [
            'title' => 'required|string',
            'reference_number' => 'required|string'
        ];

        $revisedParts = $this->letterNeedRevision();

        foreach ($revisedParts as $partName) {
            $rules["revisedFiles.$partName"] = 'required|file|mimes:pdf|max:1048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'reference_number.required' => 'Reference number is required',
            'revisedFiles.1.required' => 'Nota dinas harus ada',
            'revisedFiles.2.required' => 'SOP harus ada',
            'revisedFiles.3.required' => 'Pendukung harus ada',
        ];
    }

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with([
            'documentUploads.versions',
        ])->findOrFail($id);

        $this->fill(
            $this->letter->only('title', 'responsible_person', 'reference_number')
        );
    }

    public function save()
    {
        $this->validate();
        
        DB::transaction(function () {
            $letter = $this->letter;

            $letter->update([
                'title' => $this->title,
                'reference_number' => $this->reference_number,
            ]);

            $revisedParts = [];

            foreach ($this->revisedFiles as $partNumber => $file) {
                // Cari DocumentUpload yang sesuai dengan part_number
                $documentUpload = $this->getDocumentUploadNeedRevisions($partNumber);

                // Throw error untuk documentupload yang tidak ada part_number    
                if (!$documentUpload) {
                    throw new \Exception("DocumentUpload not found for part_number: $partNumber");
                }

                // Simpan file ke storage
                $path = $file->store('documents', 'public');

                // Meload versions secara eager
                $documentUpload = $documentUpload->load('versions');

                $revision = $documentUpload->versions()
                    ->where('is_resolved', false)
                    // ->whereNull('file_path')
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$revision) {
                    throw new \Exception("No valid revision found for part_number: $partNumber");
                }

                // Update data
                $letter->update([
                    'active_revision' => false,
                    'need_review' => true,
                ]);

                $documentUpload->update([
                    'need_revision' => false,
                ]);

                $revision->update([
                    'file_path' => $path,
                ]);

                $partName = $documentUpload->part_number_label;
                $revisedParts[] = $partName;
            }

            if ($letter->isDirty()) {
                $letter->save();
            }

            $letter->refresh();

            $letter->logStatusRevision($this->notes, $revisedParts);

            DB::afterCommit(function () use ($letter) {
                $letter->sendProcessServiceRequestNotification();
            });

            session()->flash('status', [
                'variant' => 'success',
                'message' => $letter->status->toastMessage(),
            ]);

            return $this->redirect("/history/information-system/$this->letterId", true);
        });
    }

    #[Computed]
    public function checkNotResolvedDocuments()
    {
        return $this->letter->documentUploads->versions->where('is_resolved', false);
    }

    #[Computed]
    public function checkDocumentUploadNeedRevision()
    {
        return $this->letter->documentUploads->contains(function ($documentUpload) {
            return $documentUpload->need_revision;
        });
    }

    public function getDocumentUploadNeedRevisions(int $partNumber)
    {
        return $this->letter->documentUploads
            ->where('part_number', $partNumber)
            ->where('need_revision', true)
            ->first();
    }

    public function letterNeedRevision()
    {
        return collect($this->letter->documentUploads)
            ->filter(fn($map) => $map->need_revision)
            ->map(fn($map) => $map->part_number)
            ->values();
    }
}
