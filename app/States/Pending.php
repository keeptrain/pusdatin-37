<?php

namespace App\States;

use App\States\LetterStatus;

class Pending extends LetterStatus
{
    public function label(): string
    {
        return 'Pending';
    }

    public function color(): string
    {
        return 'lime';
    }

    public function toastMessage(): string
    {
        return 'The letter was created successfully';
    }

    public function trackingMessage(): string
    {
        return 'Surat telah di kirim, mohon di cek berkala';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Surat baru telah diajukan oleh " . $context['responsible_person'];
    }
    public function icon(): string
    {
        return 'circle-pause';
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
