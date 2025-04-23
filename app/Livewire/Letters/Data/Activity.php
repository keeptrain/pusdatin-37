<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\Letters\RequestStatusTrack;
use Livewire\WithPagination;

class Activity extends Component
{
    use WithPagination;

    public $perPage = 5;

    public int $letterId;

    public $letter;

    public $activity;

    public $track;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = $this->getActivitiesProperty();
        $this->activity = $this->letter->requestStatusTrack;
        $this->track =$this->letter->requestStatusTrack()->where('letter_id',$this->letterId)->get();
    }

    public function detailPage(int $id)
    {
        return redirect()->route('letter.edit', [$id]);
    }

    public function processRevision(int $id)
    {
        $track = RequestStatusTrack::where('letter_id', $this->letterId)
            ->findOrFail($id);

        $track->update([
            'is_revision' => false,
        ]);
    }

    public function getActivitiesProperty()
    {
        return Letter::with(['requestStatusTrack' => fn($q) => $q->latest()])
            ->findOrFail($this->letterId);
    }
}
