<?php

namespace App\Exports;

use App\Models\PublicRelationRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PrExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
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
        $query = PublicRelationRequest::with('user');

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

        $items = $query->get();
        $total = $items->count();

        return $items->map(function ($item, $idx) use ($total) {
            return [
                // urutannya harus sama dengan headings()
                $item->user?->name ?? '—',
                $item->theme,
                $item->month_publication,
                $item->spesific_date,
                $item->status->label(),
                $item->target,
                is_array($item->links)
                    ? implode("\n", $item->links)
                    : $item->links,
                $item->created_at,
                // hanya baris pertama yg ditampilkan total
                $idx === 0 ? $total : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Penanggung Jawab',
            'Tema',
            'Bulan Usulan Publikasi',
            'Tanggal Spesifik Publikasi Media',
            'Status',
            'Sasaran',
            'Link Media',
            'Tanggal Permohonan',
            'Total Data',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet        = $event->sheet->getDelegate();
                $lastColIndex = count($this->headings());
                $colLetter    = Coordinate::stringFromColumnIndex($lastColIndex);

                // styling baris 1 & 2 di kolom Total Data
                $range = "{$colLetter}1:{$colLetter}2";
                $sheet->getStyle($range)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFA500'],
                    ],
                ]);
            },
        ];
    }
}
