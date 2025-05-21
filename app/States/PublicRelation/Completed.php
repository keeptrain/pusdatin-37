<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class Completed extends PublicRelationStatus {

    public function label(): String
    {
        return "Permohonan Selesai";
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan telah selesai dan link media yang di usulkan telah disisipkan.";
    }

    public function userNotificationMessage(array $context): string {
        return "Permohonan ini telah selesai, link sudah disisipkan";
    }

    public function color(): string
    {
        return "emerald";
    }

    public function icon(): string
    {
        return 'check-check';
    }

    public function badgeBg(): string
    {
        return 'bg-green-500';
    }

    public function percentage(): string
    {
        return '100%';
    }
    
    public function percentageBar(): string
    {
        return 'w-[100%]';
    }
}