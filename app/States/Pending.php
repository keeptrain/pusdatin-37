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
}