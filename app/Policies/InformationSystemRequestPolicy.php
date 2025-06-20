<?php

namespace App\Policies;

use App\Models\User;
use App\States\InformationSystem\Pending;
use App\States\InformationSystem\ApprovedKapusdatin;
use App\States\InformationSystem\ApprovedKasatpel;
use App\States\InformationSystem\Disposition;
use App\States\InformationSystem\Process;
use App\States\InformationSystem\Replied;
use App\States\InformationSystem\RepliedKapusdatin;
use App\Models\InformationSystemRequest;

class InformationSystemRequestPolicy
{
    protected const HEAD_DIVISION_ID = 2;
    protected const DIVISION_SI_ID = 3;
    protected const DIVISION_DATA_ID = 4;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('head_verifier');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('view si request') || $user->can('view data request');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->can('create request')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return false;
    }

    public function viewDisposition(User $user, InformationSystemRequest $systemRequest)
    {
        if ($user->can('can disposition') && $systemRequest->status instanceof Pending) {
            return true;
        }

        return false;
    }

    private function commonVerificationStatusChecking(InformationSystemRequest $systemRequest)
    {
        return $systemRequest->status instanceof Disposition || $systemRequest->status instanceof Replied;
    }

    private function checkDivisionLetter(int $division, InformationSystemRequest $systemRequest)
    {
        if ($division == static::DIVISION_SI_ID) {
            return $systemRequest->active_checking == static::DIVISION_SI_ID && $systemRequest->current_division == static::DIVISION_SI_ID;
        } elseif ($division == static::DIVISION_DATA_ID) {
            return $systemRequest->active_checking == static::DIVISION_DATA_ID && $systemRequest->current_division == static::DIVISION_DATA_ID;
        }

        return false;
    }

    private function conditionForVerification(InformationSystemRequest $systemRequest)
    {
        if (!$systemRequest->active_revision && !$systemRequest->need_review) {
            return true;
        }

        return false;
    }

    public function viewVerificationSiStep1(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('verification request si step1') && $this->checkDivisionLetter(static::DIVISION_SI_ID, $systemRequest) && $this->commonVerificationStatusChecking($systemRequest) && $this->conditionForVerification($systemRequest)) {
            return true;
        }

        return false;
    }

    public function viewVerificationDataStep1(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('verification request data step1') && $this->checkDivisionLetter(static::DIVISION_DATA_ID, $systemRequest) && $this->commonVerificationStatusChecking($systemRequest) && $this->conditionForVerification($systemRequest)) {
            return true;
        }

        return false;
    }

    public function canPerformStep1Verification(User $user, InformationSystemRequest $systemRequest): bool
    {
        return $this->viewVerificationSiStep1($user, $systemRequest) ||
            $this->viewVerificationDataStep1($user, $systemRequest);
    }

    private function conditionLetterForReview(InformationSystemRequest $systemRequest)
    {
        if (!$systemRequest->active_revision && $systemRequest->need_review) {
            return true;
        }

        return false;
    }

    public function viewReviewSiStep1(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('review revision si') && $this->checkDivisionLetter(static::DIVISION_SI_ID, $systemRequest) && $this->commonVerificationStatusChecking($systemRequest) && $this->conditionLetterForReview($systemRequest)) {
            return true;
        }

        return false;
    }

    public function viewReviewDataStep1(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('review revision data') && $this->checkDivisionLetter(static::DIVISION_DATA_ID, $systemRequest) && $this->commonVerificationStatusChecking($systemRequest) && $this->conditionLetterForReview($systemRequest)) {
            return true;
        }

        return false;
    }

    private function headDivisionLetter(InformationSystemRequest $systemRequest)
    {
        if ($systemRequest->active_checking == static::HEAD_DIVISION_ID) {
            return true;
        }

        return false;
    }

    private function commonStatusCheckingStep2(InformationSystemRequest $systemRequest): bool
    {
        return $systemRequest->status instanceof ApprovedKasatpel || $systemRequest->status instanceof RepliedKapusdatin;
    }

    public function viewVerificationStep2(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('verification request si-data step2') && $this->headDivisionLetter($systemRequest) && $this->conditionForVerification($systemRequest) && $this->commonStatusCheckingStep2($systemRequest)) {
            return true;
        }

        return false;
    }

    public function viewReviewStep2(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('review request si-data step2') && $this->headDivisionLetter($systemRequest) && $this->conditionLetterForReview($systemRequest) && $systemRequest->status instanceof RepliedKapusdatin) {
            return true;
        }

        return false;
    }


    public function actionProcessRequest(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('can process si') && $this->conditionForVerification($systemRequest) && $systemRequest->status instanceof ApprovedKapusdatin) {
            return true;
        }

        return false;
    }

    public function actionCompletedRequest(User $user, InformationSystemRequest $systemRequest): bool
    {
        if ($user->can('completed request') && $this->conditionForVerification($systemRequest) && $systemRequest->status instanceof Process) {
            return true;
        }

        return false;
    }
}
