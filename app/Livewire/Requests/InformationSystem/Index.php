<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Models\InformationSystemRequest;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    /** @var \Illuminate\Database\Eloquent\Collection */
    public $requests;

    /** @var array */
    public $selectedRequests = [];

    /** @var bool */
    public $selectAll = false;

    /** @var bool */
    public $isDeleting = false;

    public function mount()
    {
        // Load all requests for current user role
        $roleId = auth()->user()->currentUserRoleId();
        $this->requests = InformationSystemRequest::with(['user:id,name'])
            ->filterCurrentDivisionByCurrentUser($roleId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function refreshData()
    {
        // Refresh data setelah delete
        $roleId = auth()->user()->currentUserRoleId();
        $this->requests = InformationSystemRequest::with(['user:id,name'])
            ->filterCurrentDivisionByCurrentUser($roleId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.requests.information-system.index', [
            'requests' => $this->requests
        ]);
    }

    public function show(int $requestId)
    {
        return $this->redirectRoute('is.show', ['id' => $requestId], true);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRequests = $this->requests->pluck('id')->toArray();
        } else {
            $this->selectedRequests = [];
        }

        // Dispatch event untuk update checkbox state di frontend
        $this->dispatch('select-all-updated', [
            'selectAll' => $value,
            'selectedIds' => $this->selectedRequests
        ]);
    }

    public function updatedSelectedRequests()
    {
        $this->selectAll = count($this->selectedRequests) === $this->requests->count();
    }

    public function deleteSelected()
    {
        if (empty($this->selectedRequests)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        // Set loading state
        $this->isDeleting = true;
        $this->dispatch('delete-started');

        try {
            DB::beginTransaction();

            $deletedCount = count($this->selectedRequests);
            $deletedIds = $this->selectedRequests; // Simpan ID yang akan dihapus

            // Delete selected requests
            InformationSystemRequest::whereIn('id', $this->selectedRequests)->delete();

            DB::commit();

            // Reset selections
            $this->selectedRequests = [];
            $this->selectAll = false;

            // Refresh data
            $this->refreshData();

            // Emit event ke frontend untuk update DataTable
            $this->dispatch('data-deleted', [
                'deletedIds' => $deletedIds,
                'deletedCount' => $deletedCount
            ]);

            session()->flash('success', 'Data berhasil dihapus sebanyak ' . $deletedCount . ' item.');

            // Reset loading state dan refresh browser setelah delay
            $this->isDeleting = false;
            $this->dispatch('delete-completed');
        } catch (\Exception $e) {
            DB::rollback();
            $this->isDeleting = false;
            $this->dispatch('delete-error');
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function confirmDelete()
    {
        if ($this->isDeleting) {
            return;
        }

        $this->dispatch('confirm-delete', [
            'count' => count($this->selectedRequests)
        ]);
    }
}
