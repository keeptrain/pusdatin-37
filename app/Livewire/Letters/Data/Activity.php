<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\WithPagination;

class Activity extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $requestStatusTrack;

    public int $letterId;

    public $letter;

    public $activity;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = $this->getActivitiesProperty();
        $this->activity = $this->letter->requestStatusTrack;
    }

    public function getActivitiesProperty()
    {
        return Letter::with(['requestStatusTrack' => fn($q) => $q->latest()])
        ->findOrFail($this->letterId);
    }
}
