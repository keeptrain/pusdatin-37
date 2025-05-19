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

    abstract public function trackingActivity(): String;
}