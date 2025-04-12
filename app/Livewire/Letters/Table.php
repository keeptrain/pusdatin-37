<?php

namespace App\Livewire\Letters;


use App\Models\Letters\Letter;
use Livewire\WithPagination;
use Livewire\Component;

class Table extends Component
{
    use WithPagination;

    public $perPage = 10; // Default per page

    public $filterStatus = 'All';

    public $selectedLetters = [];

    public $statuses = ['All', 'New', 'Read', 'Replied', 'New', 'Read', 'Replied'];

    public function mount() {}

    public function render()
    {
        return view('livewire.letters.table', [
            'letters' => $this->loadLetters(),
        ]);
    }

    public function updatingPage()
    {
        $this->selectedLetters = []; // Reset properti saat halaman berubah
    }

    public function loadLetters()
    {
        // Cache query result untuk menghindari query ulang dalam satu request
        return cache()->remember('letters_' . $this->filterStatus . '_' . $this->perPage . '_' , now()->addMinutes(5), function () {
            $query = Letter::queryForTable()->withoutTrashed();

            if ($this->filterStatus !== 'All') {
                $query->where('status', $this->filterStatus);
            }

            return $query->paginate($this->perPage);
        });
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
