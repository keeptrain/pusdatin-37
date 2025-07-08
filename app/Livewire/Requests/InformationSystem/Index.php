<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Models\InformationSystemRequest;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class Index extends Component
{
    public $selectedRequests = [];
    public $selectAll = false;
    public $isDeleting = false;

    public function render()
    {
        return view('livewire.requests.information-system.index', [
            'requests' => $this->requests
        ]);
    }


    #[Computed]
    public function requests()
    {
        $roleId = auth()->user()->currentUserRoleId();

        return InformationSystemRequest::with(['user:id,name'])
            ->filterCurrentDivisionByCurrentUser($roleId)
            ->latest()
            ->get();
    }

    public function show(int $requestId)
    {
        return $this->redirect(route('is.show', $requestId), navigate: true);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Gunakan computed property yang sudah ada
            $this->selectedRequests = $this->requests->pluck('id')->toArray();
        } else {
            $this->selectedRequests = [];
        }

        // Hanya dispatch yang essential untuk sync checkbox
        $this->dispatch('select-all-updated', [
            'selectAll' => $value,
            'selectedIds' => $this->selectedRequests
        ]);
    }

    public function updatedSelectedRequests()
    {
        // Gunakan computed property
        $this->selectAll = count($this->selectedRequests) === $this->requests->count();
    }

    public function deleteSelected()
    {
        // Validation
        if (empty($this->selectedRequests)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        $this->performDelete();
    }

    private function performDelete()
    {
        $this->isDeleting = true;

        try {
            $deletedCount = count($this->selectedRequests);
            $deletedIds = $this->selectedRequests;

            DB::transaction(function () use ($deletedIds) {
                InformationSystemRequest::whereIn('id', $deletedIds)->delete();
            });
            $this->resetSelections();
            session()->flash('success', "Data berhasil dihapus sebanyak {$deletedCount} item.");

            $this->dispatch('delete-success-refresh', [
                'deletedCount' => $deletedCount,
                'message' => "Data berhasil dihapus sebanyak {$deletedCount} item."
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        } finally {
            $this->isDeleting = false;
        }
    }

    private function resetSelections()
    {
        $this->selectedRequests = [];
        $this->selectAll = false;
    }

    public function confirmDelete()
    {
        if ($this->isDeleting) return;

        $this->dispatch('confirm-delete', [
            'count' => count($this->selectedRequests)
        ]);
    }
}
