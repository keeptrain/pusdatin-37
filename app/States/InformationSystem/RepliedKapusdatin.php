<?php

namespace App\States\InformationSystem;

class RepliedKapusdatin extends InformationSystemStatus
{
    public static $name = 'replied_kapusdatin';
    
    public function label(): string
    {
        return 'Revisi Kapusdatin';
    }

    public function color(): string
    {
        return 'text-orange-800';
    }

    public function toastMessage(): string
    {
        return "Berhasil memberikan balasan kepada pemohon";
    }

    public function trackingMessage(?int $division): string
    {
        $resolveDivision = $this->getDivisionName($division);

        return "Permohonan layanan ini memerlukan revisi yang di kirimkan dari {$resolveDivision} , harap di periksa.";
    }

    public function userNotificationMessage(array $context): string
    {
        if (isset($context['verifikator_role'])) {
            return 'Permohonan layanan anda mendapatkan balasan dari Kapusdatin';
        }

        return 'Permohonan layanan ini direvisi oleh pemohon';
    }

    public function icon(): string
    {
        return 'send-horizontal';
    }

    public function badgeBg(): string
    {
        return 'bg-orange-100';
    }

    public function percentage(): string
    {
        return '80%';
    }

    public function percentageBar(): string
    {
        return 'w-[80%]';
    }
}