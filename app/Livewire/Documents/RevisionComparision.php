<?php

namespace App\Livewire\Documents;

use App\Models\Letters\DocumentUpload;
use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;

class RevisionComparision extends Component
{
    #[Locked]
    public int $letterId;

    public bool $revision = false;

    public $letter;

    public Collection $documentVersionsForViewer;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = $this->letters();
    }

    public function letters()
    {
        return Letter::with([
            'mapping.letterable' => function ($morphTo) {
                $morphTo->morphWith([
                    DocumentUpload::class => [
                        'version'
                    ],
                    \App\Models\Letters\LetterDirect::class => [],
                ]);
            }
        ])->findOrFail($this->letterId);
    }

    #[Computed]
    public function currentVersion()
    {
        $latestRevisions = new Collection();

        if ($this->letter && $this->letter->mapping->isNotEmpty()) {
            foreach ($this->letter->mapping as $map) {
                if ($map->letterable_type === DocumentUpload::class && $map->letterable) {
                    $documentUpload = $map->letterable;

                    $activeVersion = $documentUpload->version->first();

                    if ($documentUpload) {
                        $latestRevisions->push([
                            'part_number' => $documentUpload->part_number,
                            'part_number_label' => $documentUpload->part_number_label,
                            'file_path' => $activeVersion->file_path,
                            'version' => $documentUpload->version,
                        ]);
                    }
                }
            }
        }
        return $latestRevisions->sortBy('part_number');
    }
    
}
