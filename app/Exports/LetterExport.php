<?php

namespace App\Exports;

use App\Models\Letters\Letter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LetterExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Letter::with('user')->get()->map(function ($item) {
            return [
                'User Name'         => $item->user ? $item->user->name : '—',
                'Title'             => $item->title,
                'Reference Number'  => $item->reference_number,
                'Status'            => $item->status->label(),
                'Current Division'  => $item->current_division,
                'Created At'        => $item->created_at,
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
            'Created At',
        ];
    }
}
