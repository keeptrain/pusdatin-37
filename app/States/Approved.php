<?php

namespace App\States;

use App\States\LetterStatus;

class Approved extends LetterStatus
{
    public function label(): string
    {
        return 'Approved';
    }
    
    public function color(): string
    {
        return 'bg-green-500';
    }

    public function toastMessage(): string
    {
        return 'Letter successfully update status to approved!';
    }

    public function trackingMessage(): string
    {
        return 'Surat disetujui';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda diterima";
    }
}