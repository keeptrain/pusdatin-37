<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PusdatinProcess extends PublicRelationStatus
{

    public function label(): String
    {
        return "Proses Pusdatin";
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan sedang di proses oleh Kehumasan";
    }

    public function userNotificationMessage(array $context): string {
        return "Permohonan telah di usulkan oleh " . $context['responsible_person'];
    }

    public function color(): string
    {
        return "indigo";
    }

    public function icon(): string
    {
        return 'loader';
    }

    public function badgeBg(): string
    {
        return 'bg-indigo-500';
    }

    public function percentage(): string
    {
        return '85%';
    }

    public function percentageBar(): string
    {
        return 'w-[85%]';
    }
}
