<?php

namespace App\Livewire\Letters\Data;


use App\Models\Letters\Letter;
use Livewire\WithPagination;
use Livewire\Component;

class ApplicationTable extends Component
{
    use WithPagination;

    public $perPage = 10; // Default per page

    public $filterStatus = 'All';

    public $selectedLetters = [];

    public $statuses = ['All', 'New', 'Read', 'Replied', 'New', 'Read', 'Replied'];

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

    public function loadLetters()
    {
        $query = Letter::queryForTable()->withoutTrashed();

        if ($this->filterStatus !== 'All') {
            $query->where('status', $this->filterStatus);
        }

        return $query->paginate($this->perPage);
    }

    public function toggleSelectAll()
    {
        if (count($this->selectedLetters) === $this->letters->count()) {
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
