<?php

namespace App\States;

use App\States\LetterStatus;

class Process extends LetterStatus
{
    public function label(): string
    {
        return 'Process';
    }

    public function color(): string
    {
        return 'sky';
    }

    public function toastMessage(): string
    {
        return 'Successfully updated status to process';
    }

    public function trackingMessage(): string
    {
        return 'Surat sedang dalam proses.';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda sedang di proses oleh " . $context['verifikator'];
    }
    public function icon(): string
    {
        return 'hourglass';
    }
    public function badgeBg(): string
    {
        return 'bg-indigo-500';
    }
    public function percentage(): string
    {
        return '25%';
    }
    public function percentageBar(): string
    {
        return 'w-[25%]';
    }
}
