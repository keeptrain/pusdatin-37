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
    /** @var \Illuminate\Database\Eloquent\Collection */
    public $publicRelations;

    /** @var array */
    public $selectedPrRequest = [];

    /** @var bool */
    public $selectAll = false;

    public string $filterStatus = 'all';

    public array $statuses = [
        'all' => 'All',
        'permohonan_masuk' => 'Permohonan Masuk',
        'antrian_promkes' => 'Antrean Promkes',
        'kurasi_promkes' => 'Kurasi Promkes',
        'antrian_pusdatin' => 'Antrean Pusdatin',
        'proses_pusdatin' => 'Proses Pusdatin',
        'completed' => 'Selesai',
    ];

    public array $allowedStatuses = [];

    public string $sortBy = 'date_created';

    public string $searchQuery = '';

    public function mount()
    {
        $this->allowedStatuses = $this->getAllowedStatusesByRole();
        $this->loadData();
    }

    public function loadData()
    {
        // Load all requests for current user role
        $this->publicRelations = PublicRelationRequest::select('id', 'user_id', 'completed_date', 'month_publication', 'theme', 'status')
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

    public function refreshData()
    {
        // Refresh data setelah delete
        $this->loadData();
    }

    #[Title('Permohonan Kehumasan')]
    public function render()
    {
        return view('livewire.requests.public-relation.index', [
            'publicRelations' => $this->publicRelations
        ]);
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
    }

    public function updatedSelectedPrRequest()
    {
        $this->selectAll = count($this->selectedPrRequest) === $this->publicRelations->count();
    }

    public function stringMonthPublicationToNumber($searchQuery)
    {
        $monthValue = null;

        // If input is month name
        foreach (range(1, 12) as $monthNumber) {
            $monthName = Carbon::create(null, $monthNumber)->locale('id')->isoFormat('MMMM');
            if (str_contains(strtolower($monthName), $searchQuery)) {
                $monthValue = $monthNumber;
                break;
            }
        }

        // If input is number
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

    public function getAllowedStatusesByRole(): array
    {
        return PublicRelationStatus::statusesBasedRole(auth()->user());
    }

    public function updatedAllowedStatuses($value): void
    {
        // Limit maximum 7 statuses
        if (count($this->allowedStatuses) > 7) {
            $this->allowedStatuses = array_slice($this->allowedStatuses, 0, 7);
        }

        $allStatuses = array_keys($this->statuses);

        // Check if all status selected
        $allSelected = empty(array_diff($allStatuses, $this->allowedStatuses));

        if ($allSelected) {
            $this->allowedStatuses = array_merge($allStatuses, ['all']);
        }

        $this->allowedStatuses = array_unique($this->allowedStatuses);

        if (empty($this->allowedStatuses)) {
            $this->allowedStatuses = $this->getAllowedStatusesByRole();
        }

        // Refresh data after status change
        $this->loadData();
    }

    private function getSortCriteria(): array
    {
        return match ($this->sortBy) {
            'date_created' => ['created_at', 'desc'],
            default => ['updated_at', 'desc'],
        };
    }

    public function deleteSelected()
    {
        if (empty($this->selectedPrRequest)) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        try {
            DB::beginTransaction();

            $deletedCount = count($this->selectedPrRequest);
            $deletedIds = $this->selectedPrRequest; // Simpan ID yang akan dihapus

            // Delete selected requests
            PublicRelationRequest::whereIn('id', $this->selectedPrRequest)->delete();

            DB::commit();

            // Reset selections
            $this->selectedPrRequest = [];
            $this->selectAll = false;

            // Refresh data
            $this->refreshData();

            // Emit event ke frontend untuk update DataTable
            $this->dispatch('data-deleted', [
                'deletedIds' => $deletedIds,
                'deletedCount' => $deletedCount
            ]);

            session()->flash('success', 'Data berhasil dihapus sebanyak ' . $deletedCount . ' item.');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function confirmDelete()
    {
        $this->dispatch('confirm-delete', [
            'count' => count($this->selectedPrRequest)
        ]);
    }

    // Update search query and refresh data
    public function updatedSearchQuery()
    {
        $this->loadData();
    }

    // Update filter status and refresh data
    public function updatedFilterStatus()
    {
        $this->loadData();
    }

    // Update sort by and refresh data
    public function updatedSortBy()
    {
        $this->loadData();
    }
}
