<?php

namespace App\Policies;

use App\Models\User;
use App\States\PublicRelation\PusdatinProcess;
use App\States\PublicRelation\PusdatinQueue;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\Pending;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PromkesQueue;

class PublicRelationRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view pr request');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return  $user->can('view pr request');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return $user->hasRole('pr_verifier|promkes_verifier');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return false;
    }

    private function statusCheckingForQueue(PublicRelationRequest $publicRelationRequest)
    {
        return $publicRelationRequest->status instanceof Pending;
    }

    public function queuePromkes(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return $user->can('queue pr promkes') && $this->statusCheckingForQueue($publicRelationRequest);
    }

    private function statusCheckingForCuration(PublicRelationRequest $publicRelationRequest): bool
    {
        return $publicRelationRequest->status instanceof PromkesQueue;
    }

    public function curationPromkes(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return $user->can('curation') && $this->statusCheckingForCuration($publicRelationRequest);
    }

    private function statusCheckingForQueuePusdatin(PublicRelationRequest $publicRelationRequest): bool
    {
        return $publicRelationRequest->status instanceof PromkesComplete;
    }

    public function queuePusdatin(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return $user->can('queue pr pusdatin') && $this->statusCheckingForQueuePusdatin($publicRelationRequest);
    }

    public function processPusdatin(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return $user->can('process pr pusdatin') && $publicRelationRequest->status instanceof PusdatinQueue;
    }

    public function completedRequest(User $user, PublicRelationRequest $publicRelationRequest): bool
    {
        return $user->can('completing pr request') && $publicRelationRequest->status instanceof PusdatinProcess;
    }
}
