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
}