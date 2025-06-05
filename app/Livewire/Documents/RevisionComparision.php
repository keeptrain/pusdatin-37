<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
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

    public function letters()
    {
        return Letter::with([
            'documentUploads.activeVersion',
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
        $currentVersionsData = new Collection();

        if ($this->siDataRequest && $this->siDataRequest->documentUploads->isNotEmpty()) {
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

        if ($this->siDataRequest && $this->siDataRequest->documentUploads->isNotEmpty()) {
            foreach ($this->siDataRequest->documentUploads as $documentUpload) {
                // Ambil ID versi aktif
                $activeVersionId = $documentUpload->activeVersion?->id;

                // Filter versi yang tidak aktif
                $versions = $documentUpload->versions->filter(function ($version) use ($activeVersionId) {
                    return $version->id !== $activeVersionId;
                });

                // Kelompokkan data berdasarkan version
                foreach ($versions as $version) {
                    $groupedVersions->push([
                        'version' => $version->version ?? null,
                        'part_number' => $documentUpload->part_number,
                        'part_number_label' => $documentUpload->part_number_label,
                        'file_path' => Storage::url($version->file_path),
                    ]);
                }
            }
        }

        // Kelompokkan data berdasarkan version
        return $groupedVersions->groupBy('version')->map(function ($group) {
            return [
                'version' => $group->first()['version'], // Pastikan mengakses array
                'details' => $group->map(function ($item) {
                    return [
                        'part_number' => $item['part_number'],
                        'part_number_label' => $item['part_number_label'],
                        'file_path' => $item['file_path'],
                    ];
                })->values(),
            ];
        })->values();
    }
}
