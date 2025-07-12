<?php

namespace App\Livewire\Requests\PublicRelation;

use Livewire\Attributes\Title;
use App\Models\PublicRelationRequest;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\States\PublicRelation\PublicRelationStatus;
use Flux\Flux;

class Index extends Component
{
    public array $selectedDataId = [];
    public array $allowedStatuses = [];
    public bool $selectAll = false;
    public bool $isDeleting = false;


    #[Title('Permohonan Kehumasan')]
    public function render()
    {
        return view('livewire.requests.public-relation.index', [
            'publicRelations' => $this->publicRelations,
        ]);
    }

    public function mount()
    {
        $this->allowedStatuses = $this->getAllowedStatuses;
    }

    #[Computed]
    public function publicRelations()
    {
        return PublicRelationRequest::select('id', 'user_id', 'completed_date', 'month_publication', 'theme', 'status')
            ->with('user:id,name')
            ->get();
    }

    #[Computed]
    public function initiateStatusBasedRole()
    {
        return PublicRelationStatus::statusesBasedRole(auth()->user());
    }

    public function deleteSelected()
    {
        // Validation for empty selected requests
        if (empty($this->selectedDataId)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        $this->performDelete();
    }

    private function performDelete()
    {
        try {
            $deletedCount = count($this->selectedDataId);
            $deletedIds = $this->selectedDataId;

            DB::transaction(function () use ($deletedIds) {
                PublicRelationRequest::whereIn('id', $deletedIds)->delete();
                DB::afterCommit(function () {
                    Flux::modal('confirm-deletion')->close();
                });
            });

            // Reset state
            $this->resetSelections();
            session()->flash('success', "Data berhasil dihapus sebanyak {$deletedCount} item.");

            $this->redirectRoute('pr.index', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    private function resetSelections()
    {
        $this->selectedDataId = [];
        $this->selectAll = false;
    }

    public function refresh()
    {
        $this->redirectRoute('pr.index', navigate: true);
    }
}
