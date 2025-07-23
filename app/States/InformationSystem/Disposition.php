<?php

namespace App\States\InformationSystem;

use App\States\InformationSystem\InformationSystemStatus;

class Disposition extends InformationSystemStatus
{
    public static $name = 'disposition';
    
    public function label(): string
    {
        return 'Didisposisikan';
    }

    public function color(): string
    {
        return 'text-purple-800';
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

        return "Permohonan layanan didisposisikan dari Kapusdatin";
    }

    public function icon(): string
    {
        return 'locate';
    }

    public function badgeBg(): string
    {
        return 'bg-purple-100';
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
