<?php

namespace App\States;

use App\States\LetterStatus;

class Process extends LetterStatus
{
    public function label(): string
    {
        return 'Proses';
    }

    public function color(): string
    {
        return 'sky';
    }

    public function toastMessage(): string
    {
        return 'Berhasil memproses permohonan layanan';
    }

    public function trackingMessage(?int $division): string
    {
        $divisionName = $this->getDivisionName($division);

        return "Permohonan layanan sedang diproses oleh Kepala Satuan Pelaksana {$divisionName}";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan ini memerlukan verifikasi" . $context['verifikator'];
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
        return '50%';
    }

    public function percentageBar(): string
    {
        return 'w-[50%]';
    }
}
