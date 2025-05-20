<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PusdatinQueue extends PublicRelationStatus
{

    public function label(): String
    {
        return "Antrean Pusdatin";
    }

    public function trackingActivity(): String
    {
        return "Permohonan layanan sedang dalam antrean di Pusat Data Teknologi dan Dinas Kesehatan";
    }

    public function color(): string
    {
        return "blue";
    }

    public function icon(): string
    {
        return 'ticket-check';
    }

    public function badgeBg(): string
    {
        return 'bg-indigo-500';
    }

    public function percentage(): string
    {
        return '60%';
    }

    public function percentageBar(): string
    {
        return 'w-[60%]';
    }
}
