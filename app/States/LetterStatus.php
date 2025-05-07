<?php

namespace App\States;

use App\States\Pending;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<\App\Models\Letters\Letter>
 */
abstract class LetterStatus extends State
{
    abstract public function color(): string;

    abstract public function toastMessage(): string;

    abstract public function trackingMessage(): string;

    abstract public function userNotificationMessage(array $context): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowAllTransitions()
            // ->allowTransition(Pending::class, Process::class)
            // ->allowTransition(Process::class, Approved::class)
            // ->allowTransition(Process::class, Replied::class)
            // ->allowTransition(Process::class, Rejected::class)
            // ->allowTransition(Replied::class, Approved::class)
            // ->allowTransition(Replied::class, Rejected::class)

            // // Only for testing
            // ->allowTransition(Replied::class, Replied::class)
            // ->allowTransition(Replied::class, Process::class)
            // ->allowTransition(Approved::class, Process::class)
            // ->allowTransition(Rejected::class, Process::class)
        ;
    }

}
