<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Models\InformationSystemRequest;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $selectedRequests = [];
    public $selectAll = false;
    public $isDeleting = false;

    public function render()
    {
        // Load data fresh setiap render
        $roleId = auth()->user()->currentUserRoleId();
        $requests = InformationSystemRequest::with(['user:id,name'])
            ->filterCurrentDivisionByCurrentUser($roleId)
            ->latest()
            ->get();

        return view('livewire.requests.information-system.index', [
            'requests' => $requests
        ]);
    }

    public function show(int $requestId)
    {
        return $this->redirect(route('is.show', $requestId), navigate: true);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $roleId = auth()->user()->currentUserRoleId();
            $currentRequests = InformationSystemRequest::filterCurrentDivisionByCurrentUser($roleId)->get();
            $this->selectedRequests = $currentRequests->pluck('id')->toArray();
        } else {
            $this->selectedRequests = [];
        }

        $this->dispatch('select-all-updated', [
            'selectAll' => $value,
            'selectedIds' => $this->selectedRequests
        ]);
    }

    public function updatedSelectedRequests()
    {
        $roleId = auth()->user()->currentUserRoleId();
        $totalRequests = InformationSystemRequest::filterCurrentDivisionByCurrentUser($roleId)->count();
        $this->selectAll = count($this->selectedRequests) === $totalRequests;
    }

    public function deleteSelected()
    {
        if (empty($this->selectedRequests)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        $this->performDelete();
    }

    private function performDelete()
    {
        $this->isDeleting = true;
        $this->dispatch('delete-started');

        try {
            $deletedCount = count($this->selectedRequests);
            $deletedIds = $this->selectedRequests;

            DB::transaction(function () use ($deletedIds) {
                // Hapus data dari database
                InformationSystemRequest::whereIn('id', $deletedIds)->delete();
            });

            // Reset selections setelah berhasil delete
            $this->resetSelections();

            // Flash message
            session()->flash('success', "Data berhasil dihapus sebanyak {$deletedCount} item.");

            // Dispatch event untuk auto refresh halaman
            $this->dispatch('delete-success-refresh', [
                'deletedCount' => $deletedCount,
                'message' => "Data berhasil dihapus sebanyak {$deletedCount} item."
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            $this->dispatch('delete-error');
        } finally {
            $this->isDeleting = false;
            $this->dispatch('delete-completed');
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
