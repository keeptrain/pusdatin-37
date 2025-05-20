<?php

namespace App\States;

use App\States\LetterStatus;

class Pending extends LetterStatus
{
    public function label(): string
    {
        return 'Permohonan Masuk';
    }

    public function color(): string
    {
        return 'amber';
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
        return "Permohonan layanan dari " . $context['responsible_person'] . " perlu disposisi" ;
    }

    public function icon(): string
    {
        return 'folder-check';
    }

    public function badgeBg(): string
    {
        return 'bg-gray-500';
    }

    public function percentage(): string
    {
        return '10%';
    }

    public function percentageBar(): string
    {
        return 'w-[10%]';
    }
}
