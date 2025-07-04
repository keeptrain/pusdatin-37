<?php

namespace App\Livewire\Requests\PublicRelation;

use App\Models\PublicRelationRequest;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public $perPage = 10; // Default per page

    public $filterStatus = 'all';

    public array $statuses = [
        'all' => 'All',
        'antrian_promkes' => 'Antrean Promkes',
        'kurasi_promkes' => 'Kurasi Promkes',
        'antrian_pusdatin' => 'Antrean Pusdatin',
        'proses_pusdatin' => 'Proses Pusdatin',
        'completed' => 'Selesai',
    ];

    public $sortBy = 'date_created';

    public $selectedPrRequest = [];

    public $searchQuery = '';

    #[Computed]
    public function publicRelations()
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

    public function show(int $id)
    {
        return $this->redirect("public-relation/{$id}", true);
    }

    private function getSortCriteria(): array
    {
        return match ($this->sortBy) {
            'date_created' => ['created_at', 'desc'],
            'latest_activity' => ['updated_at', 'desc'],
            default => ['updated_at', 'desc'],
        };
    }

    public function deleteSelected()
    {
        PublicRelationRequest::whereIn('id', $this->selectedPrRequest)->delete();

        $this->selectedPrRequest = [];

        $this->redirect('public-relation', navigate: true);
    }

    public function render()
    {
        return view('livewire.requests.public-relation.index');
    }
}
