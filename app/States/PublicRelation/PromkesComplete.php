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
}
