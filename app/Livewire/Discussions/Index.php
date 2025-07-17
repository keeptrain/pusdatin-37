<?php

namespace App\Livewire\Discussions;

use App\Enums\Division;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Discussion;
use App\Livewire\Forms\DiscussionForm;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public int $perPage;

    public DiscussionForm $form;
    public Collection $requests;

    public bool $hasActiveFilters = false;
    public string $search = '';
    public string $sort = 'Update terbaru';
    public string $status = 'open';
    public string $discussableType = 'all';
    public array $imagesUpload = [];

    public function mount(int $perPage = 5)
    {
        $this->perPage = $perPage;
    }

    #[Title('Forum Diskusi')]
    public function render()
    {
        $query = $this->loadDiscussions();

        $query->when($this->discussableType === 'yes', function ($query) {
            $query->whereHasMorph('discussable', [InformationSystemRequest::class, PublicRelationRequest::class]);
        });

        $query->when($this->discussableType === 'no', function ($query) {
            $query->whereHasMorph('discussable', [Role::class]);
        });

        // Check if any filters are active
        $this->setActiveFiltersFlag();

        // Get discussions with pagination
        $discussions = $query->paginate($this->perPage);

        return view('livewire.discussions.index', [
            'discussions' => $discussions,
            'totalDiscussions' => $discussions->total(),
            'hasActiveFilters' => $this->hasActiveFilters
        ]);
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

            $this->dispatch('discussion-created');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function loadDiscussions(): Builder
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

    protected function applyDiscussionFilters(Builder $query): void
    {
        $currentRole = auth()->user()->currentUserRoleId();

        if ($currentRole === Division::HEAD_ID->value) {
            return;
        }

        $roleMapping = [
            Division::SI_ID->value => 'forInformationSystemDivision',
            Division::DATA_ID->value => 'forInformationSystemDivision',
            Division::PR_ID->value => 'forPublicRelationDivision',
            Division::PROMKES_ID->value => 'forPublicRelationDivision',
        ];

        $scope = $roleMapping[$currentRole] ?? 'forUserRole';
        $query->$scope($currentRole);
    }

    protected function setActiveFiltersFlag(): void
    {
        $this->hasActiveFilters = !empty($this->search) ||
            $this->discussableType !== 'all' ||
            $this->status !== 'open' ||
            $this->sort !== 'Update terbaru';
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

    public function resetFilters()
    {
        $this->reset('search', 'discussableType', 'status');
        $this->sort = 'Update terbaru';
    }

    public function removeTemporaryImage($index)
    {
        $this->form->removeAttachments($index);
    }
}
