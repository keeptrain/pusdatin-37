<?php

namespace App\Livewire\Requests\PublicRelation;

use Carbon\Carbon;
use Livewire\Attributes\Title;
use App\Models\PublicRelationRequest;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\States\PublicRelation\PublicRelationStatus;

class Index extends Component
{
    public $selectedDataId = [];
    public $selectAll = false;
    public $isDeleting = false;

    #[Title('Permohonan Kehumasan')]
    public function render()
    {
        // $this->dispatch('public-relations-data-ready');
        return view('livewire.requests.public-relation.index', [
            'publicRelations' => $this->publicRelations,
            'allowedStatuses' => $this->allowedStatuses
        ]);
    }

    #[Computed]
    public function publicRelations()
    {
        return PublicRelationRequest::select('id', 'user_id', 'completed_date', 'month_publication', 'theme', 'status')
            ->with('user:id,name')
            ->get();
    }

    #[Computed]
    public function allowedStatuses()
    {
        return PublicRelationStatus::statusesBasedRole(auth()->user());
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedDataId = $this->publicRelations->pluck('id')->toArray();
        } else {
            $this->selectedDataId = [];
        }

        $this->dispatch('select-all-updated', [
            'selectAll' => $value,
            'selectedIds' => $this->selectedPrRequest
        ]);
    }

    public function updatedSelectedPrRequest()
    {
        $this->selectAll = count($this->selectedPrRequest) === $this->publicRelations->count();
    }


    public function deleteSelected()
    {
        // Validation for empty selected requests
        if (empty($this->selectedPrRequests)) {
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

    // Helper methods
    private function stringMonthPublicationToNumber($searchQuery)
    {
        $monthValue = null;

        foreach (range(1, 12) as $monthNumber) {
            $monthName = Carbon::create(null, $monthNumber)->locale('id')->isoFormat('MMMM');
            if (str_contains(strtolower($monthName), $searchQuery)) {
                $monthValue = $monthNumber;
                break;
            }
        }

        if (is_numeric($searchQuery) && $searchQuery >= 1 && $searchQuery <= 12) {
            $monthValue = (int) $searchQuery;
        }

        return $monthValue;
    }
}
