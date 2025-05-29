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
        return 'text-red-800';
    }

    public function toastMessage(): string
    {
        return 'Berhasil menolak permohonan layanan';
    }

    public function trackingMessage(?int $division): string
    {
        $resolvedDivisonName = $this->getDivisionName($division);
        if ($division == 2 ) {
            return "Permohonan layanan anda ditolak oleh " . $resolvedDivisonName;
        }
        return "Permohonan layanan anda ditolak oleh Kasatpel " . $resolvedDivisonName;
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda ditolak" ;
    }

    public function icon(): string
    {
        return 'x';
    }

    public function badgeBg(): string
    {
        return 'bg-red-100';
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
