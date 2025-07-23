<?php

namespace App\States\InformationSystem;

use App\States\InformationSystem\InformationSystemStatus;

class Replied extends InformationSystemStatus
{
    public static $name = 'replied';
    
    public function label(): string
    {
        return 'Revisi Kasatpel';
    }

    public function color(): string
    {
        return 'text-amber-800';
    }

    public function icon(): string
    {
        return 'send-horizontal';
    }

    public function badgeBg(): string
    {
        return 'bg-amber-100';
    }

    public function percentage(): string
    {
        return '50%';
    }

    public function percentageBar(): string
    {
        return 'w-[50%]';
    }

    public function toastMessage(): string
    {
        return "Berhasil memberikan revisi kepada pemohon";
    }

    public function trackingMessage(?int $division): string
    {
        $resolveDivision = $this->getDivisionName($division);

        if ($resolveDivision == 'Kepala Pusat Dinas Kesehatan') {
            return "Permohonan layanan ini memerlukan revisi yang di kirimkan dari {$resolveDivision}, harap di periksa.";
        }

        return "Permohonan layanan ini memerlukan revisi yang di kirimkan dari Kepala Satuan Pelaksana {$resolveDivision} , harap di periksa.";
    }

    public function userNotificationMessage(array $context): string
    {
        if (isset($context['verifikator_role'])) {
            return 'Permohonan layanan anda memerlukan revisi';
        }

        return 'Permohonan layanan ini direvisi oleh pemohon';
    }
}
