<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class Pending extends PublicRelationStatus {

    public function label(): String
    {
        return "Permohonan Masuk";
    }

    public function trackingActivity(): String
    {
        return "Permohonan layanan sudah kami terima, mohon tunggu konfirmasi selanjutnya.";
    }

    public function color(): string
    {
        return "amber";
    }

    public function icon(): string
    {
        return 'folder-check';
    }

    public function badgeBg(): string
    {
        return 'bg-gray-500';
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