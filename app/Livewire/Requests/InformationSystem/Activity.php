<?php

namespace App\Livewire\Requests\InformationSystem;

use Livewire\Component;
use App\Models\InformationSystemRequest;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use App\Services\TrackingStepped;

class Activity extends Component
{
    #[Locked]
    public $systemRequestId;

    public $status;

    private $systemRequest;

    public function mount($id)
    {
        $this->systemRequestId = $id;
        $this->loadSystemRequest();
        $this->status = $this->systemRequest->status->label();
    }

    private function loadSystemRequest()
    {
        try {
            $this->systemRequest = InformationSystemRequest::with('requestStatusTrack:statusable_id,statusable_type,action,notes,created_at')->findOrFail($this->systemRequestId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Activity Information System Request not found.');
        }
    }

    #[Computed]
    public function groupedActivities()
    {
        return $this->systemRequest->getGroupedRequestStatusTracks();
    }

    #[Computed]
    public function statuses()
    {
        return TrackingStepped::SiDataRequest($this->systemRequest);
    }

    #[Computed]
    public function currentStatus()
    {
        if ($this->systemRequest && $this->status) {
            return $this->systemRequest->status->label();
        }

        return (new \App\States\InformationSystem\Pending($this->systemRequest))->label();
    }

    #[Computed]
    public function isRejected()
    {
        return $this->currentStatus() === (new \App\States\InformationSystem\Rejected($this->systemRequest))->label();
    }

    private function isReplied()
    {
        return $this->currentStatus() === (new \App\States\InformationSystem\Replied($this->systemRequest))->label();
    }

    #[Computed]
    public function currentIndex()
    {
        $statuses = $this->statuses();
        return TrackingStepped::currentIndex($this->systemRequest, $statuses);
    }
}
