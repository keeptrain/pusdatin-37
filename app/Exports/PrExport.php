<?php

namespace App\Exports;

use App\Models\PublicRelationRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct(?string $startDate = null, ?string $endDate = null, ?string $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->status    = $status;
    }

    public function collection()
    {

        $query = PublicRelationRequest::with('user')->query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->status && $this->status !== 'all') {


            $statusClass = PublicRelationRequest::resolveStatusClassFromString($this->status);
            $query->whereState('status', $statusClass);
        }

        return $query
            ->get()
            ->map(function ($item) {
                return [
                    'User Name'          => $item->user ? $item->user->name : '—',
                    'Theme'              => $item->theme,
                    'Month Publication'  => $item->month_publication,
                    'Specific Date'      => $item->spesific_date,
                    'Status'             => $item->status->label(),
                    'Target'             => $item->target,
                    'Links'              => $item->links,
                    'Created At'         => $item->created_at,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Theme',
            'Month Publication',
            'Specific Date',
            'Status',
            'Target',
            'Links',
            'Created At',
        ];
    }
}
