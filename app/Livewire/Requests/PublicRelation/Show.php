<?php

namespace App\Livewire\Requests\PublicRelation;

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    #[Locked]
    public $publicRelationId;

    public $publicRelation;

    public function mount(int $id)
    {
        $this->publicRelationId = $id;
        $this->publicRelation = PublicRelationRequest::with(['documentUploads.activeVersion'])->findOrFail($this->publicRelationId);
    }
   
}
