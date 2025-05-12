<?php

namespace App\Livewire\Letters;

use App\Models\Letters\RequestStatusTrack;
use Livewire\Component;

class DetailHistory extends Component
{
    public RequestStatusTrack $track;

    public function mount(RequestStatusTrack $track)
    {
        $this->track = $track;
    }
    public function render()
    {
        return view('livewire.letters.detail-history');
    }
}
