<?php

namespace App\Livewire\Requests\PublicRelation;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    #[Locked]
    public $publicRelationId;

    public $publicRelations;

    public function mount(int $id)
    {
        $this->publicRelationId = $id;
        $this->publicRelations = PublicRelationRequest::with(['documentUploads.activeVersion'])->findOrFail($this->publicRelationId);
    }

    // #[Computed]
    // public function publicRelations()
    // {
    //     return PublicRelationRequest::with(['documentUploads.activeVersion'])->findOrFail($this->publicRelationId);
    // }

    #[Computed]
    public function documentUploads()
    {
        $prRequest = clone $this->publicRelations;

        // if (isset($prRequest->documentUploads)) {
        //     foreach ($prRequest->documentUploads as $document) {
        //         unset($document->activeVersion);
        //     };
        // }

        return $this->publicRelations;
    }
}
