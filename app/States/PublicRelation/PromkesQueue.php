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
}
