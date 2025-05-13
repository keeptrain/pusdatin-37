<?php

namespace App\States;

use App\States\LetterStatus;

class Rejected extends LetterStatus
{
    public function label(): string
    {
        return 'Rejected';
    }

    public function color(): string
    {
        return 'red';
    }

    public function toastMessage(): string
    {
        return 'Letter successfully update status to rejected!';
    }

    public function trackingMessage(): string
    {
        return 'Surat di tolak';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda ditolak";
    }
    public function icon(): string
    {
        return 'x';
    }
    public function badgeBg(): string
    {
        return 'bg-red-500';
    }
    public function percentage(): string
    {
        return '0%';
    }
    public function percentageBar(): string
    {
        return 'w-[0%]';
    }
}
