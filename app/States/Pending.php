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
        return 'yellow';
    }

    public function toastMessage(): string
    {
        return 'The letter is pending.';
    }

    public function trackingMessage(?int $division): string
    {
        return 'Permohonan layanan sudah kami terima, mohon tunggu konfirmasi selanjutnya.';
    }

    public function userNotificationMessage(array $context): string
    {
        return "The letter is pending for review by " . $this->getDivisionName($context['division']);
    }
}