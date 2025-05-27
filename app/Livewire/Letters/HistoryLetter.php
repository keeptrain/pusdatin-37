<?php

namespace App\Livewire\Letters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;
use App\Models\PublicRelationRequest;

class HistoryLetter extends Component
{
    use WithPagination;

    public $activeTab = 'tab1';

    public $page = 1;
    public $perPage = 5;
    public $searchQuery = '';

    #[Computed]
    public function letters()
    {
        return Letter::select('id', 'title', 'status', 'reference_number', 'active_revision', 'meeting', 'created_at',)
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function publicRelationRequests()
    {
        return PublicRelationRequest::select('id', 'theme', 'target', 'status', 'links', 'created_at')
            ->orderBy('created_at','desc')
            ->where('user_id', auth()->user()->id)
            ->paginate($this->perPage);
    }

    // Reset pagination saat search berubah
    public function updatingSearchQuery()
    {
        $this->resetPage();
    }
}
