<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Services\TrackingStepped;
use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;

class Activity extends Component
{
    #[Locked]
    public $siRequestId;

    public $status;

    private Letter $siRequest;

    public function mount($id)
    {
        $this->siRequestId = $id;
        $this->loadSiRequest();
        $this->status = $this->siRequest->status->label();
    }

    private function loadSiRequest()
    {
        try {
            $this->siRequest = Letter::with('requestStatusTrack:statusable_id,statusable_type,action,notes,created_at')->findOrFail($this->siRequestId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Activity Information System Request not found.');
        }
    }

    #[Computed]
    public function groupedActivities()
    {
        return $this->siRequest->getGroupedRequestStatusTracks();
    }

    #[Computed]
    public function statuses()
    {
        return TrackingStepped::SiDataRequest($this->siRequest);
    }

    #[Computed]
    public function currentStatus()
    {
        if ($this->siRequest && $this->status) {
            return $this->siRequest->status->label();
        }

        return (new \App\States\Pending($this->siRequest))->label();
    }

    #[Computed]
    public function isRejected()
    {
        return $this->currentStatus() === (new \App\States\Rejected($this->siRequest))->label();
    }

    private function isReplied()
    {
        return $this->currentStatus() === (new \App\States\Replied($this->siRequest))->label();
    }

    #[Computed]
    public function currentIndex()
    {
        $statuses = $this->statuses();
        return TrackingStepped::currentIndex($this->siRequest, $statuses);
    }
}
