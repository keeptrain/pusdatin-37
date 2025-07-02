<?php

namespace App\Livewire\Requests\PublicRelation;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use App\Models\PublicRelationRequest;

class Show extends Component
{
    use WithPagination;

    #[Locked]
    public $publicRelationId;

    public $publicRelation;

    public function mount(int $id)
    {
        $this->publicRelationId = $id;
        $this->publicRelation = PublicRelationRequest::with([
            'documentUploads.activeVersion'
        ])->findOrFail($this->publicRelationId);
    }

    #[Title('Detail Permohonan')]
    public function render()
    {
        return view('livewire.requests.public-relation.show');
    }

    #[Computed]
    public function applicationLetter()
    {
        // return $this->publicRelation
        //     ->documentUploads()
        //     ->where('part_number', 0)
        //     ->get();
        return $this->publicRelation
            ->documentUploads
            ->filter(function ($document) {
                return $document->part_number === 0;
            })->values();
    }

    #[Computed(persist: true, seconds: 60, cache: true)]
    private function getAllowedDocument()
    {
        // return $this->publicRelation
        //     ->documentUploads()
        //     ->where('part_number', '!=', 0)
        //     ->get();
        return $this->publicRelation
            ->documentUploads
            ->filter(function ($document) {
                return $document->part_number !== 0;
            })->values();
    }
}
