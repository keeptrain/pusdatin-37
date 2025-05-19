<?php

namespace App\Livewire\Letters\Data;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use App\Models\Documents\DocumentUpload;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;

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
            // 'responsible_person' => 'required|string',
            'reference_number' => 'required|string'
        ];

        $revisedParts = $this->letterNeedRevision(DocumentUpload::class);

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
        ];
    }

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with([
            'mapping.letterable' => function ($morphTo) {
                $morphTo->morphWith([
                    DocumentUpload::class => [
                        'versions',
                    ],
                    \App\Models\Letters\LetterDirect::class => [],
                ]);
            }, 'documentUploads'
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
                $documentUpload = $letter->mapping
                    ->map(fn($m) => $m->letterable)
                    ->whereInstanceOf(DocumentUpload::class)
                    ->first(fn($upload) => $upload->part_number == $partNumber);

                if (!$documentUpload) {
                    throw new \Exception("DocumentUpload not found for part_number: $partNumber");
                }

                // Simpan file ke storage
                $path = $file->store('documents', 'public');

                $revision = $documentUpload->version()
                    ->where('is_resolved', false)
                    // ->whereNull('file_path')
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$revision) {
                    throw new \Exception("No valid revision found for part_number: $partNumber");
                }

                // Update data
                $letter->update([
                    'need_review' => true,
                ]);

                $documentUpload->update([
                    'need_revision' => false,
                ]);

                $revision->update([
                    'file_path' => $path, // Update file path
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
                    'action' => auth()->user()->name . ' telah melakukan revisi di bagian: ' . implode(' ,', $revisedParts),
                    'created_by' => $userName,
                ]);

                if (!empty($this->notes)) {
                    $statusTrack->notes = $this->notes;
                    $statusTrack->save();
                }

                $user = User::role(['administrator', 'si_verifier'])->get();
                Notification::send($user, new NewServiceRequestNotification($letter, auth()->user()));
            }

            return redirect()->to("/letter/$this->letterId/activity")
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Create direct Letter successfully!'
                ]);
        });
    }

    public function letterNeedRevision($instanceof)
    {
        return collect($this->letter->mapping)
            ->filter(fn($map) => $map->letterable instanceof $instanceof)
            ->filter(fn($map) => $map->letterable->need_revision)
            ->map(fn($map) => $map->letterable->part_number)
            ->values();
    }
}
