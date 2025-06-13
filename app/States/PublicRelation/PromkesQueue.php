<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PromkesQueue extends PublicRelationStatus
{

    public function label(): String
    {
        return "Antrean Promkes";
    }

    public function color(): string
    {
        return "text-purpler-800";
    }

    public function badgeBg(): string
    {
        return 'bg-purple-100';
    }

    public function icon(): string
    {
        return 'ticket';
    }

    public function percentage(): string
    {
        return '15%';
    }

    public function percentageBar(): string
    {
        return 'w-[15%]';
    }

    public function toastMessage(): string
    {
        return 'Berhasil mengantrikan permohonan layanan';
    }

    public function trackingMessage(): String
    {
        return "Sedang dalam antrian Promkes";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan baru di usulkan dari " . $context['responsible_person'];
    }
}
