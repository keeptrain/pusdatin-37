<?php

namespace App\States\InformationSystem;

use App\States\InformationSystem\InformationSystemStatus;

class ApprovedKasatpel extends InformationSystemStatus
{
    public static $name = 'approved_kasatpel';
    
    public function label(): string
    {
        return 'Disetujui Kasatpel';
    }

    public function color(): string
    {
        return 'text-green-800';
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
        return 'bg-green-100';
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
