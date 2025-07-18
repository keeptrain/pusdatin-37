<?php

namespace App\States\PublicRelation;

use App\Enums\Division;
use App\Models\User;
use App\States\PublicRelation\Pending;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PublicRelationStatus extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowAllTransitions()
        ;
    }

    abstract public function color(): string;

    abstract public function badgeBg(): string;

    abstract public function percentage(): string;

    abstract public function percentageBar(): string;

    abstract public function icon(): string;

    abstract public function toastMessage(): string;

    abstract public function trackingMessage(): string;

    abstract public function userNotificationMessage(array $context): string;

    public static function statusesBasedRole(User $user): array
    {
        return match ($user->roles->pluck('id')->first()) {
            Division::HEAD_ID->value => ['kurasi_promkes'],
            Division::PR_ID->value => ['antrian_pusdatin', 'proses_pusdatin', 'completed'],
            Division::PROMKES_ID->value => ['permohonan_masuk', 'antrian_promkes', 'kurasi_promkes'],
            default => [''],
        };
    }
}