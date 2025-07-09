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
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public int $perPage;

    public DiscussionForm $form;
    public $requests;

    public string $search = '';
    public $sort = 'Update terbaru';
    public $status = 'open';
    public $discussableType = '';

    public $imagesUpload = [];

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

    // #[On('upload-images')]
    public function dispatchImages($path)
    {
        // $this->form->images = $path;
    }

    #[On('removeUploaded')]
    public function removeUpload($index)
    {
        // unset($this->form->images[$index]);
        // $this->form->images = array_values($this->form->images);
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
                $query->when(
                    $query->getModel() instanceof InformationSystemRequest,
                    fn($q) => $q->select('id', 'title')
                )->when(
                        $query->getModel() instanceof PublicRelationRequest,
                        fn($q) => $q->select('id', 'theme')
                    );
            },
            'replies' => fn($q) => $q->latest()->withCount('attachments')
        ])
            ->withCount('attachments')
            ->root();

        // Apply sorting
        $query->when($this->sort != 'Update terbaru', function ($q) {
            $q->withMax('replies as latest_reply_date', 'created_at')
                ->orderByDesc('latest_reply_date')
                ->orderByDesc('created_at'); // Fallback if no replies
        })->when($this->sort != 'Diskusi terbaru', function ($q) {
            $q->orderByDesc('created_at');
        });

        // Apply filters
        $query->when($this->status, fn($q) => $q->status($this->status))
            ->when($this->search, fn($q) => $q->where('body', 'like', '%' . $this->search . '%'));

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

    public function updatedAttachments()
    {
        dd('test');
    }

    public function removeTemporaryImage($index)
    {
        $this->form->removeAttachments($index);
    }
}
