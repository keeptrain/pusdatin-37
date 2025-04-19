<?php

namespace App\Livewire\Letters\Data;


use App\Models\Letters\Letter;
use App\States\Pending;
use Livewire\WithPagination;
use Livewire\Component;

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

    public $selectedLetters = [];

    public function mount() {}

    public function render()
    {
        return view('livewire.letters.data.application-table', [
            'letters' => $this->loadLetters(),
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
        return Letter::queryForTable()
            ->withoutTrashed()
            ->filterByStatus($this->filterStatus !== 'all' ? $this->filterStatus : null)
            ->paginate($this->perPage);
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
