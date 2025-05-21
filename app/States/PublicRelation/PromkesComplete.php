<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PromkesComplete extends PublicRelationStatus
{

    public function label(): String
    {
        return "Kurasi Promkes";
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan telah di kurasi oleh promkes";
    }

    public function userNotificationMessage(array $context): string {
        return "Permohonan baru di usulkan dari " . $context['responsible_person'];
    }

    public function color(): string
    {
        return "green";
    }

    public function icon(): string
    {
        return 'clipboard-check';
    }

    public function badgeBg(): string
    {
        return 'bg-blue-600';
    }

    public function percentage(): string
    {
        return '50%';
    }

    public function percentageBar(): string
    {
        return 'w-[50%]';
    }
}
