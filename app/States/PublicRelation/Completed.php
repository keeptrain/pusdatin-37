<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class Completed extends PublicRelationStatus
{

    public function label(): String
    {
        return "Permohonan Selesai";
    }

    public function color(): string
    {
        return "text-emerald-800";
    }

    public function badgeBg(): string
    {
        return 'bg-emerald-100';
    }

    public function icon(): string
    {
        return 'check-check';
    }

    public function percentage(): string
    {
        return '100%';
    }

    public function percentageBar(): string
    {
        return 'w-[100%]';
    }

    public function toastMessage(): string
    {
        return 'Berhasil menyelesaikan permohonan layanan';
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan telah selesai dan link media yang di usulkan telah disisipkan.";
    }

    public function userNotificationMessage(array $context): string
    {
        return "Permohonan ini telah selesai, link sudah disisipkan";
    }
}
