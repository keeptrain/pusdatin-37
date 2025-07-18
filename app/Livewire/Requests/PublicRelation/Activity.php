<?php

namespace App\Livewire\Requests\PublicRelation;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use App\Models\PublicRelationRequest;
use App\Services\TrackingStepped;

class Activity extends Component
{
    #[Locked]
    public $prRequestId;

    public $status;

    private PublicRelationRequest $prRequestInstance;

    public function mount($id)
    {
        $this->prRequestId = $id;
        $this->loadPrRequest();
        $this->status = $this->prRequestInstance->status->label();
    }

    private function loadPrRequest()
    {
        try {
            $this->prRequestInstance = PublicRelationRequest::with('trackingHistorie:requestable_id,requestable_type,message,created_at')->findOrFail($this->prRequestId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Public Relation Request not found.');
        }
    }

    #[Computed]
    public function groupedActivities()
    {
        return $this->prRequestInstance->getGroupedTrackingHistorie();
    }

    #[Computed]
    public function statuses()
    {
        return TrackingStepped::PublicRelationRequest($this->prRequestInstance);
    }

    #[Computed]
    public function currentIndex()
    {
        $statuses = $this->statuses();
        return TrackingStepped::currentIndex($this->prRequestInstance, $statuses);
    }
}
