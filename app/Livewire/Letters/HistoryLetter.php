<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Letters\RequestStatusTrack;

class HistoryLetter extends Component
{
    use WithPagination;

    // Pagination
    public $perPage = 10;

    // Filters
    public $filterStatus = 'all';   // 'all', 'pending', 'approved', etc.
    public $sortOrder    = 'oldest'; // 'newest' or 'oldest'
    public $searchQuery  = '';      // search keyword

    /**
     * Reset pagination when filters change
     */
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingSortOrder()
    {
        $this->resetPage();
    }

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    /**
     * Build and return paginated query
     */
    protected function loadTracks()
    {
        $query = RequestStatusTrack::with('letter')
            ->filterByUser(Auth::user()->name);

        if ($this->filterStatus !== 'all') {
            // 'action' adalah kolom status pada request_status_tracks
            $query->where('action', $this->filterStatus);
        }

        if ($this->searchQuery) {
            $query->whereHas('letter', function ($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('reference_number', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $direction = $this->sortOrder === 'newest' ? 'desc' : 'asc';
        $query->orderBy('created_at', $direction);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.letters.history-letter', [
            'tracks' => $this->loadTracks(),
        ]);
    }
}
