<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $letterId;

    public $letter;

    public $title;
    public $responsible_person;
    public $reference_number;
    public $body;

    public $revisedFiles = [];
    public $revisionNote;

    public function rules()
    {
        $rules = [
            'title' => 'required|string',
            'responsible_person' => 'required|string',
            'reference_number' => 'required|string'
        ];

        $revisedParts = $this->letterNeedRevision(LetterUpload::class);

        foreach ($revisedParts as $partName) {
            $rules["revisedFiles.$partName"] = 'required|file|mimes:pdf|max:1048';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        return $messages;
    }

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with([
            'mapping.letterable' => function ($morphTo) {
                $morphTo->morphWith([
                    \App\Models\Letters\LetterUpload::class => [],
                    \App\Models\Letters\LetterDirect::class => [],
                ]);
            }
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
            $user = Auth::user();

            $letter->update([
                'title' => $this->title,
                'responsible_person' => $this->responsible_person,
                'reference_number' => $this->reference_number,
                'current_revision' => $letter->current_revision + 1,
                'active_revision' => false,
            ]);

            $revisedParts = [];

            foreach ($this->revisedFiles as $partName => $file) {
                $uploadsByPart = collect($this->letter->mapping)
                    ->mapWithKeys(fn($m) => [$m->letterable->part_name => $m->letterable]);

                $letterUpload = $uploadsByPart[$partName] ?? null;

                if ($letterUpload) {
                    $path = $file->store('letter', 'public');

                    $letterUpload->update([
                        'file_path' => $path,
                        'version' => $letterUpload->version + 1,
                        'needs_revision' => false,
                        'revision_note' => null,
                        'updated_at' => now()
                    ]);
                }

                $revisedParts[] = $partName;
            }

            if ($letter->isDirty()) {
                $letter->save();
            }

            if (!empty($revisedParts)) {
                $statusTrack = $this->letter->requestStatusTrack()->create([
                    'letter_id' => $letter->id,
                    'action' => $user->name . ' telah melakukan revisi di bagian: ' . implode(', ', $revisedParts),
                    'created_by' => $user->name,
                ]);

                if (!empty($this->revisionNote)) {
                    $statusTrack->notes = $this->revisionNote;
                    $statusTrack->save();
                }
            }

            return redirect()->to("/letter/$this->letterId/activity")
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Create direct Letter successfully!'
                ]);
        });
    }

    public function updateForRevision() {}

    public function letterNeedRevision($instanceof)
    {
        return collect($this->letter->mapping)
            ->filter(fn($map) => $map->letterable instanceof $instanceof)
            ->filter(fn($map) => $map->letterable->needs_revision)
            ->map(fn($map) => $map->letterable->part_name)
            ->values();
    }
}
