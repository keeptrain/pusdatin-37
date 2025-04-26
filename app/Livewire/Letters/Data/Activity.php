<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\Letters\RequestStatusTrack;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;

class Activity extends Component
{
    use WithPagination;

    public $perPage = 5;

    #[Locked]
    public int $letterId;

    public $letter;

    public $activity;

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = $this->getStatusTrack();
        $this->activity = $this->getActivitiesProperty();
    }

    public function detailPage(int $id)
    {
        return redirect()->route('letter.edit', [$id]);
    }

    protected function getStatusTrack()
    {
        return Letter::with(['requestStatusTrack' => function ($q) {
            $q->latest();
        }])->findOrFail($this->letterId);
    }

    public function getActivitiesProperty()
    {
        return collect($this->letter->requestStatusTrack)
            ->sortByDesc('created_at')
            ->groupBy([
                fn($item) => $item->created_at->format('Y-m-d'),
                fn($item) => $item->created_at->format('H:m:s')
            ]);
    }

    public function processRevision(int $id)
    {
        $track = RequestStatusTrack::where('letter_id', $this->letterId)
            ->findOrFail($id);

        $track->update([
            'is_revision' => false,
        ]);
    }
}
