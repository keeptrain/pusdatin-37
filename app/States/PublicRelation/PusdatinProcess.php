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
}
