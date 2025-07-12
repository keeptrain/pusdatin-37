<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Models\InformationSystemRequest;
use App\States\InformationSystem\InformationSystemStatus;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Flux\Flux;

class Index extends Component
{
    public array $selectedSystemId = [];
    public array $allowedStatuses = [];
    public bool $selectAll = false;
    public bool $isDeleting = false;

    public function render()
    {
        return view('livewire.requests.information-system.index', [
            'systemRequests' => $this->systemRequests
        ]);
    }

    public function mount()
    {
        $this->allowedStatuses = $this->initiateStatusBasedRole;
    }

    #[Computed]
    public function systemRequests()
    {
        $roleId = auth()->user()->currentUserRoleId();

        return InformationSystemRequest::with(['user:id,name'])
            ->filterCurrentDivisionByCurrentUser($roleId)
            ->latest()
            ->get();
    }

    #[Computed]
    protected function initiateStatusBasedRole()
    {
        return InformationSystemStatus::statusesBasedRole(auth()->user());
    }

    public function deleteSelected()
    {
        // Validation
        // $this->authorize('view si request', $this->systemRequests);

        if (empty($this->selectedSystemId)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        $this->performDelete();
    }

    private function performDelete()
    {
        $this->isDeleting = true;

        try {
            $deletedCount = count($this->selectedSystemId);
            $deletedIds = $this->selectedSystemId;

            DB::transaction(function () use ($deletedIds) {
                InformationSystemRequest::whereIn('id', $deletedIds)->delete();
                DB::afterCommit(function () {
                    Flux::modal('confirm-deletion')->close();
                });
            });
            $this->resetSelections();
            session()->flash('success', "Data berhasil dihapus sebanyak {$deletedCount} item.");

            $this->redirectRoute('is.index', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        } finally {
            $this->isDeleting = false;
        }
    }

    private function resetSelections()
    {
        $this->selectedSystemId = [];
        $this->selectAll = false;
    }
}
