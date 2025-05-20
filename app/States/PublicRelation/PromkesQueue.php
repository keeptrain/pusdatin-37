<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class PromkesQueue extends PublicRelationStatus
{

    public function label(): String
    {
        return "Antrean Promkes";
    }

    public function trackingActivity(): String
    {
        return "Sedang dalam antrian Promkes";
    }

    public function color(): string
    {
        return "blue";
    }

    public function icon(): string
    {
        return 'ticket';
    }

    public function badgeBg(): string
    {
        return 'bg-blue-400';
    }

    public function percentage(): string
    {
        return '15%';
    }

    public function percentageBar(): string
    {
        return 'w-[15%]';
    }
}
