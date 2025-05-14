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

    public function trackingMessage(?int $division): string
    {
        return "Permohonan layanan anda ditolak";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda ditolak";
    }
}