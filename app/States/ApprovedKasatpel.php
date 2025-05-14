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
        return 'green';
    }

    public function toastMessage(): string
    {
        return 'Letter successfully update status to approved!';
    }

    public function trackingMessage($division): string
    {
        return 'Permohonan layanan anda disetujui oleh Kepala Satuan Pelaksana ' . $this->getDivisionName($division) . ', selanjutnya menunggu persetujuan dari Kepala Pusat Data dan Teknologi Dinas Kesehatan';
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan layanan anda diterima";
    }
    public function icon(): string
    {
        return 'check-circle';
    }
    public function badgeBg(): string
    {
        return 'bg-green-500';
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
