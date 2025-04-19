<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use Livewire\Attributes\On;
use App\States\LetterStatus;
use App\Models\Letters\Letter;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Letters\RequestStatusTrack;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModalConfirmation extends Component
{
    public $showModal = false;

    public int $letterId;

    public Letter $letter;

    public LetterStatus $letterStatus;

    public RequestStatusTrack $requestStatusTrack;

    public $status = '';

    public function rules()
    {
        return [
            'status' => ['required', 'string', Rule::in($this->getStatusMap()->keys())],
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Status surat tidak boleh kosong',
        ];
    }

    public function render()
    {
        return view('livewire.letters.modal-confirmation');
    }

    public function mount() {}

    #[On('modal-confirmation')]
    public function openModal(int $id)
    {
        $this->showModal = true;
        $this->letterId = $id;
        $this->letter = Letter::findOrFail($this->letterId);
        // $this->states = $this->letter->getStates();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    private function getCachedLetter()
    {
        return Cache::remember('letter_' . $this->letterId, 300, function () {
            return Letter::with('status')->findOrFail($this->letterId);
        });
    }

    public function getStatusMap()
    {
        $allowedStates = ['Approved', 'Replied', 'Rejected'];

        return collect(LetterStatus::getStateMapping())->filter(
            fn($class) => in_array(class_basename($class), $allowedStates)
        )->mapWithKeys(
            fn($class) => [
                strtolower(class_basename($class)) => $class,
            ]
        );
    }

    private function processStatusTransition()
    {
        $map = $this->getStatusMap();

        $stateClass = $map[$this->status];

        $this->letter->status->transitionTo($stateClass);
    }

    private function createStatusTrack()
    {
        RequestStatusTrack::create([
            'letter_id' => $this->letterId,
            'action' => 'jhghjggjh',
        ]);
    }

    public function save()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                $this->processStatusTransition();
                $this->createStatusTrack();
            });
        } catch (ModelNotFoundException $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Letter not found',
            ]);
            return;
        }
    }
}
