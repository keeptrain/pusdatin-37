<?php

namespace App\Livewire\Letters\Data;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;
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
            'documentUploads',
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

            if (!empty($this->revisedFiles)) {
                $userName = auth()->user()->name;
                $statusTrack = $this->letter->requestStatusTrack()->create([
                    'letter_id' => $letter->id,
                    'action' => $userName . ' telah melakukan revisi di bagian: ' . implode(' ,', $revisedParts),
                    'created_by' => $userName,
                ]);

                if (!empty($this->notes)) {
                    $statusTrack->notes = $this->notes;
                    $statusTrack->save();
                }

                $user = User::role(['administrator', 'si_verifier'])->get();
                Notification::send($user, new NewServiceRequestNotification($letter, auth()->user()));
            }

            return redirect()->to("history/information-system/$this->letterId")
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Create direct Letter successfully!'
                ]);
        });
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
