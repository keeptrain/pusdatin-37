<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use App\States\Disposition;
use Livewire\WithPagination;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;

class ApplicationTable extends Component
{
    use WithPagination;

    public $perPage = 10; // Default per page

    public $filterStatus = 'all';

    public array $statuses = [
        'all' => 'All',
        'disposition' => 'Disposition',
        'process' => 'Process',
        'replied' => 'Replied',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    public $sortBy = 'date_created';

    public $selectedLetters = [];

    public $searchQuery = '';

    public function mount() {}

    public function render()
    {
        return view('livewire.letters.data.application-table');
    }

    public function detailPageForProcess(int $id)
    {
        $letter = Letter::findOrFail($id);

        if ($letter->status == Disposition::class) {
            $letter->transitionStatusToProcess($letter->current_division);
        }
        return $this->redirect("{$id}", true);
    }

    public function detailPage(int $id)
    {
        return $this->redirect("{$id}", true);
    }

    #[Computed]
    public function letters()
    {
        [$column, $direction] = $this->getSortCriteria();

        $user = auth()->user();
        $roleNames = $user->roles()->pluck('name');
        $isAdministrator = $roleNames->contains('head_verifier');
        $allowedRoleIds = $user->roles()->pluck('id');
        
        return Letter::with([
            'user:id,name',
        ])
        ->when(!$isAdministrator, function ($query) use ($allowedRoleIds) {
            $query->whereIn('current_division', $allowedRoleIds);
        })
        ->when($this->filterStatus !== 'all', function ($query) {
            $query->filterByStatus($this->filterStatus);
        })
        ->when($this->searchQuery, function ($query) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('responsible_person', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
            });
        })
        ->orderBy($column, $direction)
        ->paginate($this->perPage);
    }

    private function getSortCriteria(): array
    {
        return match ($this->sortBy) {
            'date_created' => ['created_at', 'desc'],
            'latest_activity' => ['updated_at', 'desc'],
            default => ['updated_at', 'desc'],
        };
    }

    public function toggleSelectAll()
    {
        if ($this->filterStatus != 'all' && count($this->selectedLetters) === $this->letters->count()) {
            $this->selectedLetters = [];
        } else {
            $this->selectedLetters = $this->letters->pluck('id')->toArray();
        }
    }

    public function deleteSelected()
    {
        Letter::whereIn('id', $this->selectedLetters)->delete();

        $this->selectedLetters = [];

        $this->redirect('/letter/table', navigate: true);
    }
}
