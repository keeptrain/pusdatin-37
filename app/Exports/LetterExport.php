<?php

namespace App\Exports;

use App\Models\Letters\Letter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LetterExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
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

        $items = $query->get();
        $total = $items->count();


        return $items->map(function ($item, $idx) use ($total) {
            return [
                'Nama Penganggung Jawab' => $item->user?->name ?? '—',
                'Judul Permohonan'       => $item->title,
                'Nomor Surat'            => $item->reference_number,
                'Status'                 => $item->status->label(),
                'Divisi'                 => $item->division_label,
                'Meeting'                => $item->formatted_meetings,
                'Tanggal Pengajuan'      => $item->createdAtDMY(),
                'Total Data'             => $idx === 0 ? $total : '',   // <-- hanya baris pertama
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
            'Meeting',
            'Tanggal Pengajuan',
            'Total Data',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet         = $event->sheet->getDelegate();
                $lastColIndex  = count($this->headings());
                $colLetter     = Coordinate::stringFromColumnIndex($lastColIndex);

                // range dari row 1 sampai row 2 di kolom Total Data
                $range = "{$colLetter}1:{$colLetter}2";

                // terapkan bold + fill hijau muda
                $sheet->getStyle($range)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'C6EFCE'],
                    ],
                ]);
            },
        ];
    }
}
