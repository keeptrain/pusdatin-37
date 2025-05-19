<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;

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

    #[Computed]
    public function letter()
    {
        return Letter::with('user')->where('user_id', auth()->user()->id)
            ->paginate($this->perPage);
    }
    
}
