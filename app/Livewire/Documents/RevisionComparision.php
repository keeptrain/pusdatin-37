<?php

namespace App\Livewire\Documents;

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
            'documentUploads'

        ])->findOrFail($this->letterId);
    }

    #[Computed]
    public function currentVersion()
    {
        $latestRevisions = new Collection();

        if ($this->letter && $this->letter->documentUploads->isNotEmpty()) {
            foreach ($this->letter->documentUploads as $map) {
                $documentUpload = $map;

                $activeVersion = $documentUpload->versions->first();

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
        return $latestRevisions->sortBy('part_number');
    }
}
