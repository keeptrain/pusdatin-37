<?php

namespace App\Exports;

use App\Models\PublicRelationRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PublicRelationExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithMapping, WithTitle
{
    public function __construct(
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $status = null,
    ) {
    }

    public function collection()
    {
        $query = PublicRelationRequest::with('user:id,name,section,email,contact', 'documentUploads:documentable_type,documentable_id,part_number');

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

        return $items;
    }

    public function headings(): array
    {
        return [
            'Nama Penanggung Jawab',
            'Seksi',
            'Email',
            'Kontak',
            'Tanggal Diusulkan',
            'Tanggal Permintaan Selesai',
            'Tema',
            'Sasaran',
            'Media yang diusulkan',
            'Link Media',
        ];
    }

    public function map($item): array
    {
        $media = $this->formattedMedia($item->documentUploads);

        return [
            $item->user?->name ?? '—',
            $item->user?->section ?? '—',
            $item->user?->email ?? '—',
            $item->user?->contact ?? '—',
            $item->created_at,
            $item->completed_date,
            $item->theme,
            $item->target,
            $media,
            $item->link,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColIndex = count($this->headings());
                $colLetter = Coordinate::stringFromColumnIndex($lastColIndex);

                // styling baris 1 & 2 di kolom Total Data
                $range = "{$colLetter}1:{$colLetter}2";
                $sheet->getStyle($range)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFA500'],
                    ],
                ]);
            },
        ];
    }

    public function title(): string
    {
        return 'Laporan Permohonan Kehumasan';
    }

    public function formattedMedia($media)
    {
        $listMedia = '';
        foreach ($media as $documentUpload) {
            $listMedia .= $documentUpload->part_number_label . ', ';
        }
        return rtrim($listMedia, ', ');
    }
}
