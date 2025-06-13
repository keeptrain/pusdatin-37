<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HeadVerifierExport implements FromCollection, WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            'Letters'        => new LetterExport(),
            'Public Relation' => new PrExport(),
        ];
    }
    public function collection() {}
}
