<?php

namespace App\Livewire\Discussions;

use App\Enums\Division;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Discussion;
use App\Livewire\Forms\DiscussionForm;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends Component
{
    use WithPagination;

    // public int $perPage = null;

    public DiscussionForm $form;
    public string $mode = 'view';
    public $requests;

    public string $search = '';
    public $isClosed = '';
    public $discussableType = '';

    #[Title("Forum Diskusi")]
    public function render()
    {
        $query = $this->loadDiscussions();

        $query->when($this->isClosed === 'completed', function ($query) {
            $query->whereNotNull('closed_at');
        })
            ->when($this->discussableType === 'yes', function ($query) {
                $query->whereHasMorph('discussable', [InformationSystemRequest::class, PublicRelationRequest::class]);
            });

        $discussions = $query->paginate(5);

        return view('livewire.discussions.index', compact('discussions'));
    }

    public function mount()
    {
    }

    public function create()
    {
        try {
            $this->form->store();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function loadDiscussions()
    {
        $query = Discussion::with([
            'discussable' => function ($query) {
                // Memuat kolom berdasarkan jenis model yang terkait
                $query->when(
                    $query->getModel() instanceof InformationSystemRequest,
                    function ($q) {
                    $q->select('id', 'title'); // Untuk InformationSystem
                }
                )->when(
                        $query->getModel() instanceof PublicRelationRequest,
                        function ($q) {
                        $q->select('id', 'theme'); // Untuk PublicRelation
                    }
                    );
            },
            'replies'
        ])
            ->whereNull('parent_id')
            ->latest();

        $this->applyDiscussionFilters($query);

        return $query;
    }

    protected function applyDiscussionFilters($query)
    {
        $currentRole = auth()->user()->currentUserRoleId();

        $headDivision = Division::HEAD_ID->value;
        $siDivision = Division::SI_ID->value;
        $dataDivision = Division::DATA_ID->value;
        $prDivision = Division::PR_ID->value;

        // Head Division can see all discussions
        if ($currentRole == $headDivision) {
            return; // No filters applied for head division
        }

        if ($currentRole == $siDivision || $currentRole == $dataDivision) {
            $query->where(function ($q) use ($currentRole) {
                $q->whereHasMorph('discussable', [InformationSystemRequest::class], function ($subQuery) use ($currentRole) {
                    $subQuery->where('current_division', $currentRole);
                });

                // Add Role-based discussions in the same query context
                $q->orWhere(function ($roleQuery) use ($currentRole) {
                    $roleQuery->where('discussable_type', \Spatie\Permission\Models\Role::class)
                        ->where('discussable_id', $currentRole);
                });
            });
        } elseif ($currentRole == $prDivision) {
            $query->where(function ($q) use ($currentRole) {
                $q->whereHasMorph('discussable', [PublicRelationRequest::class]);

                // Add Role-based discussions in the same query context
                $q->orWhere(function ($roleQuery) use ($currentRole) {
                    $roleQuery->where('discussable_type', \Spatie\Permission\Models\Role::class)
                        ->where('discussable_id', $currentRole);
                });
            });
        } else {
            $query->where(function ($q) use ($currentRole) {
                $q->where('user_id', auth()->user()->id);

                // Add Role-based discussions in the same query context
                $q->orWhere(function ($roleQuery) use ($currentRole) {
                    $roleQuery->where('discussable_type', \Spatie\Permission\Models\Role::class)
                        ->where('discussable_id', $currentRole);
                });
            });
        }
    }

    public function refreshPage()
    {
        // dd($this->isClosed);
        $this->dispatch('modal-close', name: 'filter-discussion-modal');
    }
}
