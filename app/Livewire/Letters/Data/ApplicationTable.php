<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Letters\Letter;


class ApplicationTable extends Component
{
    use WithPagination;

    public $perPage = 10; // Default per page

    public $filterStatus = 'all';

    public array $statuses = [
        'all' => 'All',
        'pending' => 'Pending',
        'process' => 'Process',
        'replied' => 'Replied',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    public $sortBy = 'date_created';

    public $selectedLetters = [];

    public function mount() {}

    public function render()
    {
        return view('livewire.letters.data.application-table', [
            'letters' => $this->loadLetters()->paginate($this->perPage),
        ]);
    }

    public function detailPage(int $id)
    {
        return redirect()->route('letter.detail', [$id]);
    }

    public function editPage(int $id)
    {
        return redirect()->route('letter.edit', [$id]);
    }

    public function loadLetters()
    {
        $query = Letter::with([
            'user:id,name',
        ])->when($this->filterStatus !== 'all', function ($query) {
            $query->filterByStatus($this->filterStatus);
        });

        [$column, $direction] = $this->getSortCriteria();

        $query->orderBy($column, $direction);

        return $query;
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
