<?php

namespace App\Exports;


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
