<?php

namespace App\Livewire\Discussions;

use App\Enums\Division;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Discussion;
use App\Livewire\Forms\DiscussionForm;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public int $perPage;

    public DiscussionForm $form;
    public Collection $requests;

    public string $search = '';
    public string $sort = 'Update terbaru';
    public string $status = 'open';
    public string $discussableType = '';
    public array $imagesUpload = [];

    public function mount(int $perPage = 5)
    {
        $this->perPage = $perPage;
    }

    #[Title("Forum Diskusi")]
    public function render()
    {
        $query = $this->loadDiscussions();

        $query->when($this->discussableType === 'yes', function ($query) {
            $query->whereHasMorph('discussable', [InformationSystemRequest::class, PublicRelationRequest::class]);
        });

        $discussions = $query->paginate($this->perPage);

        return view('livewire.discussions.index', compact('discussions'));
    }

    public function updatedImagesUpload($value)
    {
        foreach ($this->imagesUpload as $image) {
            $this->form->attachments[] = $image;
        }
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
        $query = Discussion::withDiscussableDetails()
            ->withAttachmentCounts()
            ->root()
            ->status($this->status)
            ->applySort($this->sort)
            ->applySearch($this->search);

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

    public function sortToggle()
    {
        $this->sort = $this->sort == 'Update terbaru' ? 'Diskusi terbaru' : 'Update terbaru';
    }

    public function refreshPage()
    {
        $this->dispatch('modal-close', name: 'filter-discussion-modal');
        $this->resetPage();
    }

    public function removeTemporaryImage($index)
    {
        $this->form->removeAttachments($index);
    }
}
