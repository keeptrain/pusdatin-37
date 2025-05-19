<?php

namespace App\Livewire\Requests\PublicRelation;

use App\Models\PublicRelationRequest;
use App\States\PublicRelation\Pending;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PromkesQueue;
use App\States\PublicRelation\PusdatinQueue;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public $perPage = 10; // Default per page

    public $filterStatus = 'all';

    public array $statuses = [
        'all' => 'All',
        'disposition' => 'Disposition',
        'process' => 'Process',
        'replied' => 'Replied',
        'approved_kasatpel' => 'Approved by Kasatpel',
        'approved_kapusdatin' => 'Approved by Kaspudatin',
        'rejected' => 'Rejected',
    ];

    public $sortBy = 'date_created';

    public $selectedLetters = [];

    public $searchQuery = '';

    #[Computed]
    public function publicRelations()
    {
        $query = PublicRelationRequest::with('documentUploads');

        return $query->paginate($this->perPage);
    }

    public function show(int $id) {
        $prRequest = PublicRelationRequest::findOrFail($id);
        if (auth()->user()->hasRole('promkes_verifier') && $prRequest->status == Pending::class) {
            $prRequest->update([
                'status' => $prRequest->status->transitionTo(PromkesQueue::class),
            ]);
            $prRequest->requestStatusTrack()->create([
                'action' => $prRequest->status->trackingActivity(null),
                'created_by' => auth()->user()->name,
            ]);
        } elseif (auth()->user()->hasRole('head_verifier') && $prRequest->status == PromkesComplete::class){
            $prRequest->update([
                'status' => $prRequest->status->transitionTo(PusdatinQueue::class),
            ]);
            $prRequest->requestStatusTrack()->create([
                'action' => $prRequest->status->trackingActivity(null),
                'created_by' => auth()->user()->name,
            ]);
        }
        return $this->redirect("public-relation/{$id}", true);
    }

    public function render()
    {
        return view('livewire.requests.public-relation.index');
    }
}
