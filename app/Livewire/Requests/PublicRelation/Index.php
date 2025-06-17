<?php

namespace App\Livewire\Requests\PublicRelation;

use Carbon\Carbon;
use Livewire\Attributes\Title;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\PublicRelationRequest;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\States\PublicRelation\PublicRelationStatus;

class Index extends Component
{
    use WithPagination;

    public int $perPage = 10; // Default per page

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

    public array $previousAllowedStatuses = [];

    public array $oldAllowedStatuses = [];

    public string $sortBy = 'date_created';

    public array $selectedPrRequest = [];

    public string $searchQuery = '';

    public function mount()
    {
        $this->allowedStatuses = $this->getAllowedStatusesByRole();
    }

    public function show(int $id): void
    {
        $this->redirectRoute('pr.show', $id, navigate: true);
    }

    #[Title('Permohonan Kehumasan')]
    public function render(): object
    {
        return view('livewire.requests.public-relation.index');
    }

    #[Computed]
    public function publicRelations(): LengthAwarePaginator
    {
        return $this->getPaginatedPublicRelations();
    }

    protected function buildBaseQuery()
    {
        return PublicRelationRequest::select('id', 'user_id', 'completed_date', 'month_publication', 'theme', 'status')
            ->with('user:id,name')
            ->filterByStatuses($this->allowedStatuses);
    }

    protected function getPaginatedPublicRelations(): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery()
            ->when($this->filterStatus !== 'all', fn($q) => $q->filterByStatus($this->filterStatus))
            ->when($this->searchQuery, fn($q) => $this->applySearch($q))
            ->when(
                $this->sortBy === 'completed_date',
                fn($q) => $q->orderBy('completed_date', 'asc'),
                fn($q) => $q->orderBy(...$this->getSortCriteria())
            );

        return $query->paginate($this->perPage);
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


    protected function getCacheKey(): string
    {
        return sprintf(
            'public-relations-%s-%s-%s-%s-%s',
            $this->filterStatus,
            $this->searchQuery,
            $this->sortBy,
            $this->perPage,
            $this->getPage()
        );
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

        // State before change
        $oldStatuses = $this->oldAllowedStatuses;
        $this->oldAllowedStatuses = $this->allowedStatuses;

        $allStatuses = array_keys($this->statuses);

        // Detect change state "all"
        $wasAllChecked = in_array('all', $oldStatuses);
        $isAllCheckedNow = in_array('all', $this->allowedStatuses);

        // If user unchecked "all"
        if ($wasAllChecked && !$isAllCheckedNow) {
            $this->allowedStatuses = $this->previousAllowedStatuses;
            $this->previousAllowedStatuses = [];
        }
        // If user checked "all"
        elseif (!$wasAllChecked && $isAllCheckedNow) {
            $this->previousAllowedStatuses = array_diff($this->allowedStatuses, ['all']);
            $this->allowedStatuses = array_merge($allStatuses, ['all']);
        }
        // If change on status other than "all"
        else {
            // If "all" active when other status changed
            if ($isAllCheckedNow) {
                $this->allowedStatuses = array_diff($this->allowedStatuses, ['all']);
            }

            // Check if all status selected
            $allSelected = empty(array_diff($allStatuses, $this->allowedStatuses));

            if ($allSelected) {
                $this->allowedStatuses = array_merge($allStatuses, ['all']);
            }
        }

        $this->allowedStatuses = array_unique($this->allowedStatuses);

        if (empty($this->allowedStatuses)) {
            $this->allowedStatuses = $this->getAllowedStatusesByRole();
        }
    }

    private function getSortCriteria(): array
    {
        return match ($this->sortBy) {
            'date_created' => ['created_at', 'desc'],
            default => ['updated_at', 'desc'],
        };
    }

    public function deleteSelected(): void
    {
        DB::transaction(function () {
            PublicRelationRequest::whereIn('id', $this->selectedPrRequest)->delete();
            $this->selectedPrRequest = [];
        });

        $this->redirectRoute('pr.index', navigate: true);
    }
}
