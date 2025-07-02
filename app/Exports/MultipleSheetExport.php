<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetExport implements WithMultipleSheets
{

    public function __construct(
        public ?string $division,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $status = null,
    ) {
    }

    public function sheets(): array
    {
        return [
            'Sistem Informasi' => new InformationSystemExport($this->division, $this->startDate, $this->endDate, $this->status),
            'Kehumasan' => new PublicRelationExport($this->startDate, $this->endDate, $this->status),
        ];
    }
}