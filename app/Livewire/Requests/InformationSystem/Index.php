<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Models\InformationSystemRequest;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class Index extends Component
{
    public $selectedDataId = [];
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
            $this->selectedDataId = $this->requests->pluck('id')->toArray();
        } else {
            $this->selectedDataId = [];
        }

        // Hanya dispatch yang essential untuk sync checkbox
        $this->dispatch('select-all-updated', [
            'selectAll' => $value,
            'selectedIds' => $this->selectedDataId
        ]);
    }

    public function updatedSelectedDataId()
    {
        // Gunakan computed property
        $this->selectAll = count($this->selectedDataId) === $this->requests->count();
    }

    public function deleteSelected()
    {
        // Validation
        if (empty($this->selectedDataId)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        $this->performDelete();
    }

    private function performDelete()
    {
        $this->isDeleting = true;

        try {
            $deletedCount = count($this->selectedDataId);
            $deletedIds = $this->selectedDataId;

            DB::transaction(function () use ($deletedIds) {
                InformationSystemRequest::whereIn('id', $deletedIds)->delete();
            });
            $this->resetSelections();
            session()->flash('success', "Data berhasil dihapus sebanyak {$deletedCount} item.");

            $this->redirectRoute('pr.index', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        } finally {
            $this->isDeleting = false;
        }
    }

    private function resetSelections()
    {
        $this->selectedDataId = [];
        $this->selectAll = false;
    }

    public function confirmDelete()
    {
        if ($this->isDeleting)
            return;

        $this->dispatch('confirm-delete', [
            'count' => count($this->selectedDataId)
        ]);
    }
}
