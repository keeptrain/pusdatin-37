<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PromkesComplete extends PublicRelationStatus
{
    public function label(): String
    {
        return "Kurasi Promkes";
    }

    public function color(): string
    {
        return "text-green-800";
    }

    public function badgeBg(): string
    {
        return 'bg-green-100';
    }

    public function icon(): string
    {
        return 'clipboard-check';
    }

    public function percentage(): string
    {
        return '50%';
    }

    public function percentageBar(): string
    {
        return 'w-[50%]';
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan telah di kurasi oleh promkes";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan dari " . $context['responsible_person'] . " membutuhkan disposisi kehumasan";
    }
}
