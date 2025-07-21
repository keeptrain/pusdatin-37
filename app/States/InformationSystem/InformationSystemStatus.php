<?php

namespace App\States\InformationSystem;

use App\Enums\Division;
use App\Models\User;
use App\States\InformationSystem\Pending;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;


/**
 * @extends State<\App\Models\InformationSystemRequest>
 */
abstract class InformationSystemStatus extends State
{
    const DIVISION_MAP = [
        2 => 'Kepala Pusat Data dan Teknologi Dinas Kesehatan',
        3 => 'Sistem Informasi',
        4 => 'Pengelolaan Data',
        5 => 'Hubungan Masyarakat',
    ];

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

    abstract public function trackingMessage(?int $division): string;

    abstract public function userNotificationMessage(array $context): string;

    protected function getDivisionName($division): string
    {
        return self::DIVISION_MAP[$division] ?? 'Unknown Division';
    }

    public static function statusesBasedRole(User $user): array
    {
        $userRole = $user->currentUserRoleId();
        $headDataStatuses = ['Permohonan Masuk', 'Didisposisikan', 'Disetujui Kasatpel', 'Revisi Kapusdatin', 'Disetujui Kapusdatin'];
        $siDataStatuses = ['Didisposisikan', 'Revisi Kasatpel', 'Disetujui Kasatpel', 'Disetujui Kapusdatin', 'Proses'];

        return match ($userRole) {
            Division::HEAD_ID->value => $headDataStatuses,
            Division::SI_ID->value => $siDataStatuses,
            Division::DATA_ID->value => $siDataStatuses,
            default => [],
        };
    }
}
