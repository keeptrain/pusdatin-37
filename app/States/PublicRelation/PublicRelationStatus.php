<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\Pending;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PublicRelationStatus extends State {

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowAllTransitions()
        ;
    }

    abstract public function label(): String;

    abstract public function trackingMessage(): String;

    abstract public function userNotificationMessage(array $context): string;

    abstract public function icon(): string;

    abstract public function color():string;

    abstract public function badgeBg(): string;

    abstract public function percentage(): string;

    abstract public function percentageBar(): string;
}