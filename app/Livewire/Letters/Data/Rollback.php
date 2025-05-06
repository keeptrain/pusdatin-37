<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\RequestStatusTrack;

class Rollback extends Component
{
    public $letterId;

    public $letter;

    public $status;

    public $changeStatus = "";

    public $statusTrack;

    public array $trackId = [];

    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = $this->getStatusTrack();
    }

    public function getStatusTrack()
    {
        return Letter::with(['requestStatusTrack' => function ($q) {
            $q->where('created_by', auth()->user()->name);
            $q->latest();
        }])->findOrFail($this->letterId);
    }

    public function save()
    {
        DB::transaction(function () {
            $this->letter->transitionStatusOnly($this->changeStatus);

            $this->letter->update([
                'active_revision' => false
            ]);

            $this->letter->mapping->each(function ($mapping) {
                if ($mapping->letterable) {
                    $mapping->letterable->update([
                        'needs_revision' => false,
                    ]);
                }
            });

            $validIds = $this->letter->requestStatusTrack
                ->where('created_by', auth()->user()->name)
                ->pluck('id')
                ->intersect($this->trackId);

            if ($validIds->isNotEmpty()) {
                RequestStatusTrack::whereIn('id', $validIds)->delete();
            }

            return redirect()->to("/letter/$this->letterId/activity")
                ->with('status', [
                    'variant' => 'success',
                    'message' => 'Create direct Letter successfully!'
                ]);
        });
    }
}
