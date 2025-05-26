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
        'approved_kasatpel' => 'Approved by Kasatpel',
        'approved_kapusdatin' => 'Approved by Kaspudatin',
        'rejected' => 'Rejected',
    ];

    public $sortBy = 'date_created';

    public $selectedLetters = [];

    public $searchQuery = '';

    public function mount() {}

    public function detailPageForProcess(int $id)
    {
        $letter = Letter::findOrFail($id);

        if ($letter->status == Disposition::class) {
            $letter->transitionStatusToProcess($letter->current_division);
            $letter->logStatus(null);
        }
        return $this->redirect("{$id}", true);
    }

    public function detailPage(int $id)
    {
        return $this->redirect("{$id}", true);
    }

    #[Computed()]
    public function letters()
    {
        // Kriteria sorting
        [$column, $direction] = $this->getSortCriteria();

        $query = Letter::with(['user:id,name']);

        // Filter berdasarkan pengguna saat ini
        $query->filterByCurrentUser();

        // Filter berdasarkan status jika filterStatus tidak 'all'
        if ($this->filterStatus !== 'all') {
            $query->filterByStatus($this->filterStatus);
        }

        // Filter berdasarkan search query
        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('responsible_person', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }

        $query->orderBy($column, $direction);

        return $query->paginate($this->perPage);
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
