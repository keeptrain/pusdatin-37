<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class Pending extends PublicRelationStatus {

    public function label(): String
    {
        return "Permohonan Masuk";
    }

    public function trackingMessage(): String
    {
        return "Permohonan layanan sudah kami terima, mohon tunggu konfirmasi selanjutnya.";
    }

    public function userNotificationMessage(array $context): string {
        return "Permohonan baru telah di usulkan dari " . $context['responsible_person'];
    }

    public function color(): string
    {
        return "text-yellow-800";
    }

    public function icon(): string
    {
        return 'folder-check';
    }

    public function badgeBg(): string
    {
        return 'bg-yellow-100';
    }

    public function percentage(): string
    {
        return '10%';
    }

    public function percentageBar(): string
    {
        return 'w-[10%]';
    }
}