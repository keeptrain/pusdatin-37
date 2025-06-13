<?php

namespace App\States;

use App\States\LetterStatus;

class ApprovedKapusdatin extends LetterStatus
{
    public function label(): string
    {
        return 'Disetujui Kapusdatin';
    }

    public function color(): string
    {
        return 'text-emerald-800';
    }

    public function toastMessage(): string
    {
        return 'Berhasil menyetujui permohonan layanan';
    }

    public function trackingMessage($division): string
    {
        return 'Permohonan layanan anda telah disetujui oleh Kepala Pusat Data dan Teknologi Dinas Kesehatan' ;
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan ini telah mendapatkan persetujuan kapusdatin";
    }

    public function icon(): string
    {
        return 'check-check';
    }

    public function badgeBg(): string
    {
        return 'bg-emerald-100';
    }

    public function percentage(): string
    {
        return '100%';
    }
    
    public function percentageBar(): string
    {
        return 'w-[100%]';
    }
}
