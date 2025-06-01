<?php

namespace App\Exports;

use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HeadVerifierExport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            'Letters'        => new LetterExport(),
            'Public Relation' => new PrExport(),
        ];
    }
}
