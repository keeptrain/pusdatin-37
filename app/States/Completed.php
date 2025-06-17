<?php

namespace App\States;

use App\States\LetterStatus;

class Completed extends LetterStatus
{
    public function label(): string
    {
        return 'Permohonan Selesai';
    }

    public function color(): string
    {
        return 'text-teal-800';
    }

    public function badgeBg(): string
    {
        return 'bg-teal-100';
    }

    public function icon(): string
    {
        return 'check-circle';
    }

    public function percentage(): string
    {
        return '100%';
    }

    public function percentageBar(): string
    {
        return 'w-[100%]';
    }

    public function toastMessage(): string
    {
        return 'Berhasil menyelesaikan permohonan';
    }

    public function trackingMessage(?int $division): string
    {
        $divisionName = $this->getDivisionName($division);

        return "Permohonan layanan telah selesai di kerjakan oleh divisi {$divisionName}";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan ini perlu disposisi sistem informasi / data";
    }
}