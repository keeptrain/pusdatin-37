<?php

namespace App\States;

use App\States\LetterStatus;

class ApprovedKasatpel extends LetterStatus
{
    public function label(): string
    {
        return 'Approved by Kasatpel';
    }

    public function color(): string
    {
        return 'emerald';
    }

    public function toastMessage(): string
    {
        return 'Berhasil menyetujui permohonan layanan';
    }

    public function trackingMessage($division): string
    {
        return 'Permohonan layanan disetujui oleh Kepala Satuan Pelaksana ' . $this->getDivisionName($division) . ', selanjutnya menunggu persetujuan dari Kepala Pusat Data dan Teknologi Dinas Kesehatan';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan ini telah mendapatkan persetujuan kasatpel";
    }

    public function icon(): string
    {
        return 'check';
    }

    public function badgeBg(): string
    {
        return 'bg-green-400';
    }

    public function percentage(): string
    {
        return '75%';
    }

    public function percentageBar(): string
    {
        return 'w-[75%]';
    }
}
