<?php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<\App\Models\Letters\Letter>
 */
abstract class LetterStatus extends State
{
    const DIVISION_MAP = [
        2 => 'Kepala Pusat Dinas Kesehatan',
        3 => 'Sistem Informasi',
        4 => 'Pengelolaan Data',
        5 => 'Hubungan Masyarakat',
    ];

    abstract public function color(): string;

    abstract public function badgeBg(): string;

    abstract public function percentage(): string;

    abstract public function percentageBar(): string;

    abstract public function icon(): string;

    abstract public function toastMessage(): string;

    abstract public function trackingMessage(?int $division): string;

    abstract public function userNotificationMessage(array $context): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowAllTransitions()
        ;
    }

    protected function getDivisionName($division):string
    {
        return self::DIVISION_MAP[$division] ?? 'Unknown Division';
    }
}
