<?php

namespace App\Livewire\Letters\Data;

use App\States\Pending;
use App\States\Process;
use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;

class Detail extends Component
{
    #[Locked]
    public int $letterId;

    public ?Letter $letter;
    
    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->letter = Letter::with([
            'documentUploads.activeVersion:id,file_path'
        ])->findOrFail($id);
    }

    #[Computed]
    public function availablePart()
    {
        return $this->letter->documentUploads
            ->map(function ($upload) {
                return [
                    'part_number' => $upload->part_number,
                    'part_number_label' => $upload->part_number_label,
                ];
            })
            ->values()
            ->toArray();
    }

    public function backPending()
    {
        $letter = Letter::findOrFail($this->letterId);
        $letter->status->transitionTo(Pending::class);
        return $this->redirect("/letter/$this->letterId", true);
    }

    public function backProcess()
    {
        $letter = Letter::findOrFail($this->letterId);
        $letter->status->transitionTo(Process::class);
        $letter->active_revision = false;
        $letter->save();
        return $this->redirect("/letter/$this->letterId", true);
    }
}
