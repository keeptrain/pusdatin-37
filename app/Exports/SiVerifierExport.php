<?php

namespace App\Exports;

use App\Models\Letters\Letter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiVerifierExport implements FromCollection, WithHeadings
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
        $query = Letter::with('user')
            ->where('current_division', 3);

        // Jika ada tanggal mulai, filter created_at >= startDate
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        // Jika ada tanggal akhir, filter created_at <= endDate
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->status && $this->status !== 'all') {
            // filter berdasarkan status
            $statusClass = Letter::resolveStatusClassFromString($this->status);
            $query->whereState('status', $statusClass);
        }

        // Ambil data kemudian map ke array
        return $query->get()->map(function ($item) {
            return [
                'User Name'        => $item->user ? $item->user->name : '—',
                'Title'            => $item->title,
                'Reference Number' => $item->reference_number,
                'Status'           => $item->status->label(),
                // 'Current Division' => $item->current_division,
                // 'Active Revision'  => $item->active_revision,
                // 'Need Review'      => $item->need_review,
                'Meeting'          => $item->meeting,
                'Created At'       => $item->createdAtDMY(),
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Nama Penanggung Jawab',
            'Judul Permohonan',
            'Nomor Surat',
            'Status',
            // 'Current Division',
            // 'Active Revision',
            // 'Need Review',
            'Link Meeting',
            'Tanggal Pengajuan',
        ];
    }
}
