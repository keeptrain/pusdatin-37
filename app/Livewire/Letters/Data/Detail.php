<?php

namespace App\Livewire\Letters\Data;

use App\States\Process;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Detail extends Component
{
    public ?Letter $letter;

    public int $letterId;

    public $letterUpload = null;

    public $letterDirect = null;

    public function mount(int $id)
    {
        $this->letterId = $id;

        $this->getLetter();
    }

    public function getLetter()
    {
        return once(
            fn() =>
            $this->letter = Letter::with('letterable')->findOrFail($this->letterId)
        );
    }

    public function processLetter(int $id)
    {
        if (Auth::user()->withoutRole('user')) {
            DB::transaction(function () {
                $this->letter->transitionToStatus(Process::class);
            });

            return redirect()->route('letter.table')->with([
                'status' => [
                    'variant' => 'success',
                    'message' => 'Letter has update to read status!'
                ]
            ]);
        } else {
            return redirect()->route('letter.detail', [$id])->with([
                'status' => [
                    'variant' => 'error',
                    'message' => 'Letter cannot update!'
                ]
            ]);
        }
    }

    public function repliedLetter($letterId)
    {
        $this->dispatch('modal-confirmation', $letterId);
    }

    public function backStatus()
    {
        $this->letter->status->transitionTo(Process::class);
    }

}
