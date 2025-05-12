<?php

namespace App\Livewire\Letters;

use App\Models\Letters\RequestStatusTrack;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HistoryLetter extends Component
{
    public $tracks;

    public function mount()
    {
        $userName = Auth::user()->name; // atau kolom lain sesuai yang Anda simpan di created_by
        $this->tracks = RequestStatusTrack::with('letter')
            ->filterByUser($userName)
            ->sortBy('latest')       // pakai scopeSortBy jika perlu
            ->get();
    }

    public function render()
    {
        return view('livewire.letters.history-letter');
    }
}
