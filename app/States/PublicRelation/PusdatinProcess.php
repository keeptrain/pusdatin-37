<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PusdatinProcess extends PublicRelationStatus
{

    public function label(): String
    {
        return "Proses Pusdatin";
    }

    public function color(): string
    {
        return "text-blue-800";
    }

    public function badgeBg(): string
    {
        return 'bg-blue-100';
    }

    public function icon(): string
    {
        return 'loader';
    }

    public function percentage(): string
    {
        return '85%';
    }

    public function percentageBar(): string
    {
        return 'w-[85%]';
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan sedang di proses oleh Kehumasan";
    }

    public function userNotificationMessage(array $context): string {
        return "Permohonan ini telah di kurasi dan didisposisikan";
    }
}
