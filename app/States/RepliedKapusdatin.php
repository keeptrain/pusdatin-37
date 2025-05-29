<?php

namespace App\States;

class RepliedKapusdatin extends LetterStatus
{
    public function label(): string
    {
        return 'Balasan Kapusdatin';
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

        if ($resolveDivision === 'Kepala Pusat Dinas Kesehatan') {
            return "Permohonan layanan mendapatkan balasan dari {$resolveDivision}, harap di periksa.";
        }
        
        return "Permohonan layanan mendapatkan balasan dari Kepala Satuan Pelaksana {$resolveDivision} , harap di periksa.";
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