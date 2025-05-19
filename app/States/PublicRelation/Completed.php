<?php

namespace App\States\PublicRelation;

use App\States\PublicRelation\PublicRelationStatus;

class Completed extends PublicRelationStatus {

    public function label(): String
    {
        return "Permohonan Selesai";
    }

    public function trackingActivity(): String
    {
        return "Permohonan layanan telah selesai dan link media yang di usulkan telah disisipkan.";
    }
}