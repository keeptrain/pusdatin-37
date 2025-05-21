<?php

namespace App\States;

use App\States\LetterStatus;

class Disposition extends LetterStatus
{
    public function label(): string
    {
        return 'Disposisi';
    }

    public function color(): string
    {
        return 'yellow';
    }

    public function toastMessage(): string
    {
        return 'Berhasil mendisposisikan permohonan layanan';
    }

    public function trackingMessage(?int $division): string
    {
        $divisionName = $this->getDivisionName($division);

        return "Permohonan layanan anda di disposisikan oleh Kepala Pusat Data dan Teknologi Dinas Kesehatan 
                ke Kepala Satuan Pelaksana {$divisionName}";
    }

    public function userNotificationMessage(array $context): string
    {

        return "Permohonan layanan telah diajukan oleh " . $context['responsible_person'];
    }

    public function icon(): string
    {
        return 'locate';
    }

    public function badgeBg(): string
    {
        return 'bg-zinc-500';
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
