<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PusdatinProcess extends PublicRelationStatus
{

    public function label(): String
    {
        return "Proses pusdatin";
    }

    public function trackingActivity(): String
    {
        return "Permohonan layanan sedang dalam proses Pusat Data Teknologi dan Dinas Kesehatan";
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
