<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

class RevisionComparision extends Component
{
    #[Locked]
    public int $siDataRequestId;

    private $siDataRequest;

    public function mount(int $id)
    {
        $this->siDataRequestId = $id;
        $this->siDataRequest = $this->letters();
    }

    #[Title('Perbandingan Versi')]
    public function render()
    {
        return view('livewire.documents.revision-comparision');
    }

    public function letters()
    {
        return Letter::with([
            'documentUploads.activeVersion:id,file_path',
            'documentUploads.versions'
        ])->findOrFail($this->siDataRequestId);
    }

    #[Computed]
    public function checkAvailableAnyVersions()
    {
        return $this->siDataRequest->hasNonZeroPartNumber();
    }

    #[Computed]
    public function currentVersion()
    {
        $currentVersionsData = collect();

        if ($this->siDataRequest->documentUploads->isNotEmpty()) {
            foreach ($this->siDataRequest->documentUploads as $documentUpload) {
                $currentVersionsData->push($documentUpload->formatForCurrentVersion());
            }
        }

        return $currentVersionsData->sortBy('part_number');
    }

    #[Computed]
    public function anyVersions()
    {
        $groupedVersions = collect();

        if ($this->siDataRequest->documentUploads->isNotEmpty()) {
            foreach ($this->siDataRequest->documentUploads as $documentUpload) {
                // Get active version ID
                $activeVersionId = $documentUpload->activeVersion?->id;

                $versions = $documentUpload->versions->reject(fn($version) => $version->id === $activeVersionId);

                // Group data by version
                foreach ($versions as $version) {
                    $groupedVersions->push([
                        'version' => $version->version ?? null,
                        'part_number' => $documentUpload->part_number,
                        'part_number_label' => $documentUpload->part_number_label,
                        'file_path' => Storage::url($version->file_path),
                        'revision_note' => $version->revision_note,
                    ]);
                }
            }
        }

        // Group by version
        return $groupedVersions->sortBy('version')->groupBy('version')->map(function ($group) {
            return [
                'version' => $group->first()['version'],
                'details' => $group->map(function ($item) {
                    return [
                        'part_number' => $item['part_number'],
                        'part_number_label' => $item['part_number_label'],
                        'file_path' => $item['file_path'],
                        'revision_note' => $item['revision_note'],
                    ];
                })->values(),
            ];
        })->values();
    }
}
