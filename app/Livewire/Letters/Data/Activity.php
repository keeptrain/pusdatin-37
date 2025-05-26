<?php

namespace App\Livewire\Letters\Data;

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

    private Letter $siRequestInstance;

    public function mount($id)
    {
        $this->siRequestId = $id;
        $this->loadPrRequest();
        $this->status = $this->siRequestInstance->status->label();
    }

    private function loadPrRequest()
    {
        try {
            $this->siRequestInstance = Letter::findOrFail($this->siRequestId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Letter not found.');
        }
    }

    #[Computed]
    public function groupedActivities()
    {
        return $this->siRequestInstance->getGroupedRequestStatusTracks();
    }

    #[Computed]
    public function statuses()
    {
        return TrackingStepped::SiDataRequest($this->siRequestInstance);
    }

    #[Computed]
    public function currentStatus()
    {
        if ($this->siRequestInstance && $this->status) {
            return $this->siRequestInstance->status->label();
        }

        return (new \App\States\Pending($this->siRequestInstance))->label();
    }

    #[Computed]
    public function isRejected()
    {
        return $this->currentStatus() === (new \App\States\Rejected($this->siRequestInstance))->label();
    }

    private function isReplied()
    {
        return $this->currentStatus() === (new \App\States\Replied($this->siRequestInstance))->label();
    }

    #[Computed]
    public function currentIndex()
    {
        $statuses = $this->statuses();
        return TrackingStepped::currentIndex($this->siRequestInstance, $statuses);
    }
}
