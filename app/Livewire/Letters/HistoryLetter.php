<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Letters\RequestStatusTrack;

class HistoryLetter extends Component
{
    use WithPagination;

    public $perPage     = 2;
    public $searchQuery = '';

    // Reset pagination saat search berubah
    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    // Computed property: hanya search, no filter/sort
    public function getTracksProperty()
    {
        return RequestStatusTrack::with('letter')
            ->filterByUser(Auth::user()->name)
            ->when(
                $this->searchQuery,
                fn($q) => $q->whereHas(
                    'letter',
                    fn($q2) =>
                    $q2->where('title', 'like', "%{$this->searchQuery}%")
                        ->orWhere('reference_number', 'like', "%{$this->searchQuery}%")
                )
            )
            ->latest('created_at')
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.letters.history-letter', [
            'tracks' => $this->tracks,
        ]);
    }
}
