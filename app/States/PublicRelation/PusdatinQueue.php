<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PusdatinQueue extends PublicRelationStatus
{

    public function label(): String
    {
        return "Antrean Pusdatin";
    }

    public function color(): string
    {
        return "text-amber-800";
    }

    public function badgeBg(): string
    {
        return 'bg-amber-100';
    }

    public function icon(): string
    {
        return 'ticket-check';
    }

    public function percentage(): string
    {
        return '60%';
    }

    public function percentageBar(): string
    {
        return 'w-[60%]';
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan sedang dalam antrean di Pusat Data Teknologi dan Dinas Kesehatan";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan ini membutuhkan disposisi kehumasan";
    }
}
