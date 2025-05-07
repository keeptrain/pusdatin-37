<?php

namespace App\Livewire\Letters\Data;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\RequestStatusTrack;
use Livewire\Attributes\Locked;

class Rollback extends Component
{
    #[Locked]
    public $letterId;

    public $changeStatus = '';

    public array $filter = [
        'sortBy' => 'latest',
        'deletedRecords' => 'withoutDeleted',
    ];

    public array $trackId = [];

    public function mount(int $id)
    {
        $this->letterId = $id;
    }

    #[Computed]
    public function letter(): Letter
    {
        return Letter::with([
            'requestStatusTrack' => fn($query) =>
            $query->filterByUser(auth()->user()->name)
                ->sortBy($this->filter['sortBy'])
                ->withDeletedRecords($this->filter['deletedRecords'])
        ])->findOrFail($this->letterId);
    }

    public function updatedFilter()
    {
        $this->trackId = [];
    }

    public function save()
    {
        DB::transaction(function () {
            $letter = $this->letter;

            $letter->transitionStatusOnly($this->changeStatus);
            $letter->update(['active_revision' => false]);

            $letter->mapping->each(function ($mapping) {
                if ($mapping->letterable) {
                    $mapping->letterable->update([
                        'needs_revision' => false,
                    ]);
                }
            });

            $validIds = $letter->requestStatusTrack
                ->pluck('id')
                ->intersect($this->trackId);

            if ($validIds->isNotEmpty()) {
                $tracks = RequestStatusTrack::withTrashed()->whereIn('id', $validIds)->get();

                foreach ($tracks as $track) {
                    $track->trashed() ? $track->restore() : $track->delete();
                }
            }

            return redirect()->to("/letter/{$this->letterId}/activity")
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Successfully rollback action!'
                ]);
        });
    }

    public function delete()
    {
        DB::transaction(function () {});
    }
}
