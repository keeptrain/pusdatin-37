<?php

namespace App\Livewire\Requests\InformationSystem;

use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    #[Locked]
    public int $systemRequestId;

    public Letter $systemRequest;

    public $notes = '';

    public function mount(int $id)
    {
        $this->systemRequestId = $id;
        $this->systemRequest = Letter::with('documentUploads.activeVersion:id,file_path')->findOrFail($this->systemRequestId);
    }

    #[Computed]
    public function allowedParts()
    {
        return $this->systemRequest->allowedParts();
    }

    public function inputNotes()
    {
        $systemRequest = $this->systemRequest;
        
        DB::transaction(function () use ($systemRequest) {

            $existingNotes = $systemRequest->notes;

            $existingNotes[] = $this->notes;

            $systemRequest->update(['notes' => $existingNotes]);

            $this->reset('notes');
        });
    }

    public function backPending()
    {
        $letter = Letter::findOrFail($this->systemRequestId);
        $letter->status->transitionTo(Pending::class);
        return $this->redirect("/letter/$this->systemRequestId", true);
    }

    public function backProcess()
    {
        $letter = Letter::findOrFail($this->systemRequestId);
        $letter->status->transitionTo(Process::class);
        $letter->active_revision = false;
        $letter->save();
        return $this->redirect("/letter/$this->systemRequestId", true);
    }
}
