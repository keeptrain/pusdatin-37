<?php

namespace App\States\InformationSystem;

use App\States\InformationSystem\InformationSystemStatus;

class Pending extends InformationSystemStatus
{
    public function label(): string
    {
        return 'Permohonan Masuk';
    }

    public function color(): string
    {
        return 'text-yellow-800';
    }

    public function badgeBg(): string
    {
        return 'bg-yellow-100';
    }

    public function icon(): string
    {
        return 'folder-check';
    }

    public function percentage(): string
    {
        return '10%';
    }

    public function percentageBar(): string
    {
        return 'w-[10%]';
    }

    public function toastMessage(): string
    {
        return 'Berhasil mengajukan permohonan layanan';
    }

    public function trackingMessage(?int $division): string
    {
        return 'Permohonan layanan sudah kami terima, mohon tunggu konfirmasi selanjutnya.';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan ini perlu disposisi sistem informasi / data";
    }
}
