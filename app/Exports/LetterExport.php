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
                    'Current Division' => $item->division_label,
                    'Meeting'          => $item->formatted_meetings,
                    'Created At'       => $item->createdAtDMY(),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Penganggung Jawab',
            'Judul Permohonan',
            'Nomor Surat',
            'Status',
            'Divisi',
            // 'Active Revision',
            // 'Need Review',
            'Meeting',
            'Tanggal Pengajuan',
        ];
    }
}
