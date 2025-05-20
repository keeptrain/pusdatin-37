<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PromkesComplete extends PublicRelationStatus
{

    public function label(): String
    {
        return "Kurasi Promkes";
    }

    public function trackingActivity(): String
    {
        return "Permohonan layanan telah di kurasi oleh promkes";
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
