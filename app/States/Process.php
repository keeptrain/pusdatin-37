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
}