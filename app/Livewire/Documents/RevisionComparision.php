<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\InformationSystemRequest;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;

class RevisionComparision extends Component
{
    #[Locked]
    public int $systemRequestId;

    private ?InformationSystemRequest $systemRequest = null;

    #[Title('Perbandingan Versi')]
    public function render()
    {
        return view('livewire.documents.revision-comparision', [
            'anyVersions' => $this->transformedVersions(),
        ]);
    }

    public function mount(int $id): void
    {
        $this->systemRequestId = $id;
        $this->systemRequest = $this->loadInformationSystemRequests();
    }

    protected function loadInformationSystemRequests(): InformationSystemRequest
    {
        return InformationSystemRequest::with([
            'documentUploads.activeVersion:id,file_path',
            'documentUploads.versions'
        ])->findOrFail($this->systemRequestId);
    }

    protected function getAllVersions()
    {
        return $this->systemRequest->documentUploads
            ->flatMap(fn($doc) => $doc->versions->map(fn($v) => [$doc, $v]));
    }

    public function transformedVersions()
    {
        $storageBaseUrl = asset('storage');

        return $this->getAllVersions()
            ->filter(function ($pair) {
                [$doc, $v] = $pair;
                return !empty($v->file_path);
            })
            ->map(function ($pair) use ($storageBaseUrl) {
                [$doc, $v] = $pair;
                $isActive = $v->id === $doc->activeVersion?->id;

                return [
                    'version' => $v->version,
                    'part_number' => $doc->part_number,
                    'part_number_label' => $doc->part_number_label . ($isActive ? ' *' : ''),
                    'file_path' => "{$storageBaseUrl}/{$v->file_path}",
                    'revision_note' => $v->revision_note,
                    'created_at' => $v->created_at,
                ];
            })

            ->sortBy('version')
            ->groupBy('version')
            ->map(fn($group, $version) => [
                'version' => $version,
                'details' => $group->values(),
            ])
            ->values();
    }
}
