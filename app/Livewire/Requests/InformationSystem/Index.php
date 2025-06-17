<?php

namespace App\Livewire\Requests\InformationSystem;

use Livewire\Component;
use Livewire\WithPagination;
use App\States\LetterStatus;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10; // Default per page

    public $filterStatus = 'all';

    public array $statuses = [
        'all' => 'All',
        'pending' => 'Permohonan Masuk',
        'disposition' => 'Disposisi',
        'replied' => 'Revisi Kasatpel',
        'approved_kasatpel' => 'Disetujui Kasatpel',
        // 'replied_kapusdatin' => 'Revisi Kapusdatin',
        'approved_kapusdatin' => 'Disetujui Kapusdatin',
        'process_request' => 'Proses Permohonan',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak',
    ];

    public array $allowedStatuses = [];

    public array $previousAllowedStatuses = [];

    public array $oldAllowedStatuses = [];

    public $sortBy = 'date_created';

    public $selectedSystemRequests = [];

    public $searchQuery = '';

    public function mount()
    {
        $this->allowedStatuses = $this->getAllowedStatusesByRole($this->getCurrentRoleId);
    }

    public function render()
    {
        return view('livewire.requests.information-system.index');
    }

    public function show(int $systemRequestId)
    {
        return $this->redirectRoute('is.show', ['id' => $systemRequestId], true);
    }

    #[Computed]
    public function informationSystemRequests()
    {
        return $this->getPaginatedInformationSystem();
    }

    #[Computed]
    public function getCurrentRoleId()
    {
        return auth()->user()->currentUserRoleId();
    }

    protected function buildBaseQuery()
    {
        return Letter::select('id', 'user_id', 'title', 'current_division', 'status', 'created_at')
            ->with('user:id,name')
            ->filterCurrentDivisionByCurrentUser($this->getCurrentRoleId)
            ->filterByStatuses($this->allowedStatuses);
    }

    protected function getPaginatedInformationSystem(): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery()
            ->when($this->filterStatus !== 'all', fn($q) => $q->filterByStatus($this->filterStatus))
            ->when($this->searchQuery, fn($q) => $this->applySearch($q))
            ->when(
                $this->sortBy === 'created_at',
                fn($q) => $q->orderBy('created_at', 'asc'),
                fn($q) => $q->orderBy(...$this->getSortCriteria())
            );

        return $query->paginate($this->perPage);
    }

    protected function applySearch($query)
    {
        return $query->where(function ($q) {
            $q->where('title', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('current_division', 'like', '%' . $this->searchQuery . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchQuery . '%');
                });
        });
    }

    private function getSortCriteria(): array
    {
        return match ($this->sortBy) {
            'date_created' => ['created_at', 'desc'],
            'latest_activity' => ['updated_at', 'desc'],
            default => ['updated_at', 'desc'],
        };
    }

    public function getAllowedStatusesByRole($role): array
    {
        return LetterStatus::statusesBasedRole($role);
    }

    public function updatedAllowedStatuses($value): void
    {
        // Limit maximum 9 statuses
        if (count($this->allowedStatuses) > 9) {
            $this->allowedStatuses = array_slice($this->allowedStatuses, 0, 9);
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
            $this->allowedStatuses = $this->getAllowedStatusesByRole($this->getCurrentRoleId);
        }
    }

    public function deleteSelected()
    {
        DB::transaction(function () {
            Letter::whereIn('id', $this->selectedSystemRequests)->delete();
            $this->selectedSystemRequests = [];
        });

        $this->redirectRoute('is.index', navigate: true);
    }
}
