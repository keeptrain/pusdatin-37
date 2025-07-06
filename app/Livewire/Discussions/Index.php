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

    protected $user;
    // protected $siDivisions;
    // protected $prDivisions;
    // protected $allDivisions;

    #[Title("Forum Diskusi")]
    public function render()
    {
        $discussions = $this->loadDiscussions()->paginate(5);

        return view('livewire.discussions.index', compact('discussions'));
    }

    public function mount()
    {
        // $this->perPage = $perPage ?? 10;
        $this->user = auth()->user();

        // $this->siDivisions = [Division::SI_ID->value, Division::DATA_ID->value];
        // $this->prDivisions = [Division::PR_ID->value, Division::PROMKES_ID->value];
        // $this->allDivisions = array_merge($this->siDivisions, $this->prDivisions);
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

        $siDivisions = Division::SI_ID->value;
        $dataDivisions = Division::DATA_ID->value;
        $prDivisions = Division::PR_ID->value;

        if ($currentRole == $siDivisions || $currentRole == $dataDivisions) {
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
        } elseif ($currentRole == $prDivisions) {
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
}
