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

    public function trackingMessage(): string
    {
        return 'Surat di tolak';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda ditolak";
    }
}