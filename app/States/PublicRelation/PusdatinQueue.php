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
}
