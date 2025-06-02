<?php

namespace App\Exports;

use App\Models\Letters\Letter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LetterExport implements FromCollection, WithHeadings
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

        $query = Letter::query()->with('user');



        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->status && $this->status !== 'all') {

            $stateClass = Letter::resolveStatusClassFromString($this->status);
            $query->whereState('status', $stateClass);
        }

        return $query
            ->get()
            ->map(function ($item) {
                return [
                    'User Name'        => $item->user ? $item->user->name : '—',
                    'Title'            => $item->title,
                    'Reference Number' => $item->reference_number,
                    'Status'           => $item->status->label(),
                    'Current Division' => $item->current_division,
                    'Active Revision'  => $item->active_revision,
                    'Need Review'      => $item->need_review,
                    'Meeting'          => $item->meeting,
                    'Created At'       => $item->created_at,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Title',
            'Reference Number',
            'Status',
            'Current Division',
            'Active Revision',
            'Need Review',
            'Meeting',
            'Created At',
        ];
    }
}
