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
    public $selectedPrRequest = [];
    public $selectAll = false;
    public $isDeleting = false;


    public string $filterStatus = 'all';
    public string $sortBy = 'date_created';
    public string $searchQuery = '';

    public array $statuses = [
        'all' => 'All',
        'permohonan_masuk' => 'Permohonan Masuk',
        'antrian_promkes' => 'Antrean Promkes',
        'kurasi_promkes' => 'Kurasi Promkes',
        'antrian_pusdatin' => 'Antrean Pusdatin',
        'proses_pusdatin' => 'Proses Pusdatin',
        'completed' => 'Selesai',
    ];

    #[Title('Permohonan Kehumasan')]
    public function render()
    {
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
            ->filterByStatuses($this->allowedStatuses)
            ->when($this->filterStatus !== 'all', fn($q) => $q->filterByStatus($this->filterStatus))
            ->when($this->searchQuery, fn($q) => $this->applySearch($q))
            ->when(
                $this->sortBy === 'completed_date',
                fn($q) => $q->orderBy('completed_date', 'asc'),
                fn($q) => $q->orderBy(...$this->getSortCriteria())
            )
            ->get();
    }


    #[Computed]
    public function allowedStatuses()
    {
        return PublicRelationStatus::statusesBasedRole(auth()->user());
    }

    public function show(int $id): void
    {
        $this->redirectRoute('pr.show', $id, navigate: true);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPrRequest = $this->publicRelations->pluck('id')->toArray();
        } else {
            $this->selectedPrRequest = [];
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


    public function updatedSearchQuery()
    {
        // Auto refresh data saat search berubah (jika diperlukan)
        // Data akan otomatis ter-update karena menggunakan computed property
    }

    public function updatedFilterStatus()
    {
        // Auto refresh data saat filter berubah
        // Data akan otomatis ter-update karena menggunakan computed property
    }

    public function deleteSelected()
    {
        if (empty($this->selectedPrRequest)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        $this->performDelete();
    }

    private function performDelete()
    {
        $this->isDeleting = true;

        try {
            $deletedCount = count($this->selectedPrRequest);
            $deletedIds = $this->selectedPrRequest;

            DB::transaction(function () use ($deletedIds) {
                PublicRelationRequest::whereIn('id', $deletedIds)->delete();
            });

            // Reset state
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
        $this->selectedPrRequest = [];
        $this->selectAll = false;
    }

    public function confirmDelete()
    {
        if ($this->isDeleting) return;

        $this->dispatch('confirm-delete', [
            'count' => count($this->selectedPrRequest)
        ]);
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

    protected function applySearch($query)
    {
        $searchQuery = strtolower($this->searchQuery);

        return $query->where(function ($q) use ($searchQuery) {
            $q->where('theme', 'like', "%$searchQuery%")
                ->orWhere('month_publication', '=', $this->stringMonthPublicationToNumber($searchQuery))
                ->orWhereHas('user', function ($q) use ($searchQuery) {
                    $q->where('name', 'like', "%$searchQuery%");
                });
        });
    }

    private function getSortCriteria(): array
    {
        return match ($this->sortBy) {
            'date_created' => ['created_at', 'desc'],
            default => ['updated_at', 'desc'],
        };
    }
}
