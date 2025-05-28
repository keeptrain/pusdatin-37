<?php

namespace App\Policies;

use App\Models\User;
use App\States\ApprovedKasatpel;
use App\States\Pending;
use App\Models\Letters\Letter;
use App\States\Process;
use App\States\Replied;
use App\States\RepliedKapusdatin;

class LetterPolicy
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
    public function view(User $user, Letter $letter): bool
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
    public function update(User $user, Letter $letter): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Letter $letter): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Letter $letter): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Letter $letter): bool
    {
        return false;
    }

    public function viewDisposition(User $user, Letter $letter)
    {
        if ($user->can('can disposition') && $letter->status instanceof Pending) {
            return true;
        }

        return false;
    }

    private function commonVerificationStatusChecking(Letter $letter)
    {
        return $letter->status instanceof Process || $letter->status instanceof Replied;
    }

    private function checkDivisionLetter(int $division, Letter $letter)
    {
        if ($division == static::DIVISION_SI_ID) {
            return $letter->active_checking == static::DIVISION_SI_ID && $letter->current_division == static::DIVISION_SI_ID;
        } elseif ($division == static::DIVISION_DATA_ID) {
            return $letter->active_checking == static::DIVISION_DATA_ID && $letter->current_division == static::DIVISION_DATA_ID;
        }

        return false;
    }

    private function conditionLetterForVerification(Letter $letter)
    {
        if (!$letter->active_revision && !$letter->need_review) {
            return true;
        }

        return false;
    }

    public function viewVerificationSiStep1(User $user, Letter $letter): bool
    {
        if ($user->can('verification request si step1') && $this->checkDivisionLetter(static::DIVISION_SI_ID, $letter) && $this->commonVerificationStatusChecking($letter) && $this->conditionLetterForVerification($letter)) {
            return true;
        }

        return false;
    }

    public function viewVerificationDataStep1(User $user, Letter $letter): bool
    {
        if ($user->can('verification request data step1') && $this->checkDivisionLetter(static::DIVISION_DATA_ID, $letter) && $this->commonVerificationStatusChecking($letter) && $this->conditionLetterForVerification($letter)) {
            return true;
        }

        return false;
    }

    public function canPerformStep1Verification(User $user, Letter $letter): bool
    {
        return $this->viewVerificationSiStep1($user, $letter) ||
            $this->viewVerificationDataStep1($user, $letter);
    }

    private function conditionLetterForReview(Letter $letter)
    {
        if (!$letter->active_revision && $letter->need_review) {
            return true;
        }

        return false;
    }

    public function viewReviewSiStep1(User $user, Letter $letter): bool
    {
        if ($user->can('review revision si') && $this->checkDivisionLetter(static::DIVISION_SI_ID, $letter) && $this->commonVerificationStatusChecking($letter) && $this->conditionLetterForReview($letter)) {
            return true;
        }

        return false;
    }

    public function viewReviewDataStep1(User $user, Letter $letter): bool
    {
        if ($user->can('review revision data') && $this->checkDivisionLetter(static::DIVISION_DATA_ID, $letter) && $this->commonVerificationStatusChecking($letter) && $this->conditionLetterForReview($letter)) {
            return true;
        }

        return false;
    }

    private function headDivisionLetter(Letter $letter)
    {
        if ($letter->active_checking == static::HEAD_DIVISION_ID) {
            return true;
        }

        return false;
    }

    private function commonStatusCheckingStep2(Letter $letter): bool
    {
        return $letter->status instanceof ApprovedKasatpel || $letter->status instanceof RepliedKapusdatin;
    }

    public function viewVerificationStep2(User $user, Letter $letter): bool
    {
        if ($user->can('verification request si-data step2') && $this->headDivisionLetter($letter) && $this->conditionLetterForVerification($letter) && $this->commonStatusCheckingStep2($letter)) {
            return true;
        }

        return false;
    }

    public function viewReviewStep2(User $user, Letter $letter): bool
    {
        if ($user->can('review request si-data step2') && $this->headDivisionLetter($letter) && $this->conditionLetterForReview($letter) && $letter->status instanceof RepliedKapusdatin) {
            return true;
        }

        return false;
    }
}
