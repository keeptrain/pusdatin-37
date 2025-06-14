<?php

namespace App\Livewire\Requests\PublicRelation;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\PublicRelationRequest;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public int $perPage = 10; // Default per page

    public string $filterStatus = 'all';

    public array $statuses = [
        'all' => 'All',
        'antrian_promkes' => 'Antrean Promkes',
        'kurasi_promkes' => 'Kurasi Promkes',
        'antrian_pusdatin' => 'Antrean Pusdatin',
        'proses_pusdatin' => 'Proses Pusdatin',
        'completed' => 'Selesai',
    ];

    public string $sortBy = 'date_created';

    public array $selectedPrRequest = [];

    public string $searchQuery = '';

    #[Computed]
    public function publicRelations(): LengthAwarePaginator
    {
        [$column, $direction] = $this->getSortCriteria();

        $query = PublicRelationRequest::select('id', 'user_id', 'completed_date', 'month_publication', 'theme', 'status')->with('user:id,name')
            ->filterByRole(auth()->user()->roles()->pluck('id')->first());

        if ($this->filterStatus !== 'all') {
            $query->filterByStatus($this->filterStatus);
        }

        // Filter berdasarkan search query
        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('theme', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }
        $query->orderBy($column, $direction);

        return $query->paginate($this->perPage);
    }

    public function show(int $id): void
    {
        $this->redirectRoute('pr.show', $id,  navigate: true);
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
        PublicRelationRequest::whereIn('id', $this->selectedPrRequest)->delete();

        $this->selectedPrRequest = [];

        $this->redirectRoute('pr.index', navigate: true);
    }

    public function render(): object
    {
        return view('livewire.requests.public-relation.index');
    }
}
