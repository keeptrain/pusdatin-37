<?php

namespace App\Livewire\Letters\Data;

use App\States\Pending;
use App\States\Process;
use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class Detail extends Component
{
    #[Locked]
    public int $letterId;

    public ?Letter $letter;

    public $notes = '';

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

    public function inputNotes()
    {
        DB::transaction(function () {
            $SiRequest = $this->letter;

            $existingNotes = $SiRequest->notes;

            $existingNotes[] = $this->notes;

            $SiRequest->update(['notes' => $existingNotes]);

            $SiRequest->load('documentUploads.activeVersion:id,file_path');

            $this->reset('notes');

            // return $this->redirect("$this->letterId",true);
        });
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
