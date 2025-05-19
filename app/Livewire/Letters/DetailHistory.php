<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

class DetailHistory extends Component
{
    #[Locked]
    public $letterId;
    public $letter;

    public function mount($id)
    {
        $this->letterId = $id;
        $this->letter = $this->letters();
    }

    public function letters()
    {
        return Letter::with('requestStatusTrack')->findOrFail($this->letterId);
    }

    #[Computed]
    public function activities()
    {
        return collect($this->letter->requestStatusTrack)
            ->sortByDesc('created_at')
            ->groupBy([
                fn($item) => $item->created_at->format('Y-m-d'),
                fn($item) => $item->created_at->format('H:i:s')
            ]);
    }

    public function render()
    {
        return view('livewire.letters.detail-history');
    }
}
