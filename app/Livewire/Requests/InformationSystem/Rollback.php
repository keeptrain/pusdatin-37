<?php

namespace App\Livewire\Requests\InformationSystem;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\DB;
use App\Models\RequestStatusTrack;
use Livewire\Attributes\Locked;

class Rollback extends Component
{
    #[Locked]
    public $systemRequestId;

    public $changeStatus = '';

    public array $filter = [
        'sortBy' => 'latest',
        'deletedRecords' => 'withoutDeleted',
    ];

    public array $trackId = [];

    public function mount(int $id)
    {
        $this->systemRequestId = $id;
    }

    #[Computed]
    public function systemRequest(): InformationSystemRequest
    {
        return InformationSystemRequest::with([
            'requestStatusTrack' => fn($query) =>
            $query->filterByUser(auth()->user()->name)
                ->sortBy($this->filter['sortBy'])
                ->withDeletedRecords($this->filter['deletedRecords'])
        ])->findOrFail($this->systemRequestId);
    }

    public function updatedFilter()
    {
        $this->trackId = [];
    }

    public function save()
    {
        DB::transaction(function () {
            $systemRequest = $this->systemRequest;

            $systemRequest->transitionStatusOnly($this->changeStatus);
            $systemRequest->update(['active_revision' => false]);

            $systemRequest->mapping->each(function ($mapping) {
                if ($mapping->letterable) {
                    $mapping->letterable->update([
                        'needs_revision' => false,
                    ]);
                }
            });

            $validIds = $systemRequest->requestStatusTrack
                ->pluck('id')
                ->intersect($this->trackId);

            if ($validIds->isNotEmpty()) {
                $tracks = RequestStatusTrack::withTrashed()->whereIn('id', $validIds)->get();

                foreach ($tracks as $track) {
                    $track->trashed() ? $track->restore() : $track->delete();
                }
            }

            return redirect()->to("/information-system/{$this->systemRequestId}")
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
