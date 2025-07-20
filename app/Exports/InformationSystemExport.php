<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Enums\Division;
use App\Models\InformationSystemRequest;
use App\Models\MeetingInformationSystemRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class InformationSystemExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    public function __construct(
        public ?string $division,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $status = null,
    ) {
    }

    public function collection()
    {
        $systemRequests = InformationSystemRequest::with(['user:id,name,section,email,contact']);

        if ($this->division != Division::HEAD_ID->value) {
            $systemRequests->where('current_division', $this->division);
        }

        if ($this->startDate) {
            $systemRequests->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $systemRequests->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->status && $this->status !== 'all') {
            if ($this->status === 'in_process') {
                $inProcessStatus = InformationSystemRequest::resolveStatusClassFromArray([
                    'disposition',
                    'replied',
                    'approved_kasatpel',
                    'replied_kapusdatin',
                    'approved_kapusdatin',
                    'process_request'
                ]);
                $systemRequests->whereIn('status', $inProcessStatus);
            } else {
                $resolveStatus = InformationSystemRequest::resolveStatusClassFromString($this->status);
                $systemRequests->where('status', $resolveStatus);
            }
        }

        $resultRequests = $systemRequests->get();

        $meetings = MeetingInformationSystemRequest::whereIn('request_id', $resultRequests->pluck('id'))
            ->orderBy('start_at', 'desc')
            ->get(['request_id', 'topic', 'place', 'start_at', 'end_at', 'result']);

        // Format the results as objects with the required attributes
        $results = $resultRequests->map(function ($request) use ($meetings) {
            $requestMeetings = $meetings->where('request_id', $request->id);

            return (object) [
                'user' => $request->user ?? null,
                'request' => $request,
                'meetings' => $requestMeetings,
            ];
        });

        return $results;
    }

    public function map($item): array
    {
        // Format meetings
        $formattedMeetings = $this->formatMeetings($item->meetings);

        $map = [
            $item->user->name ?? '—',
            $item->user->section ?? '—',
            $item->user->email ?? '—',
            $item->user->contact ?? '—',
            $item->request->createdAtWithTime(),
            $item->request->title,
            $item->request->reference_number,
            $item->request->status->label(),
        ];

        // Tambahkan kolom "Kasatpel yang menangani" jika Kapusdatin
        if ($this->division == Division::HEAD_ID->value) {
            $map[] = $item->request->division_label;
        }

        // Tambahkan kolom "Meeting" dan "Total meeting"
        $map[] = $formattedMeetings;
        $map[] = $item->meetings->count();

        return $map;
    }

    public function headings(bool $isExcel = true): array
    {
        if ($isExcel) {
            return [
                'Nama Penganggung Jawab',
                'Seksi',
                'Email',
                'Kontak',
                'Tanggal Pengajuan',
                'Judul Permohonan',
                'Nomor Surat',
                'Status saat ini',
                'Meeting',
                'Total meeting',
            ];
        } else {
            $headings = [
                'Nama Penganggung Jawab',
                'Kontak',
                'Tanggal Pengajuan',
                'Judul Permohonan',
                'Nomor Surat',
                'Status saat ini',
                // 'Waktu pemrosesan',
            ];
        }

        if ($this->division == Division::HEAD_ID->value) {
            $headings[] = 'Kasatpel yang menangani';
        }

        // Tambahkan kolom "Meeting" dan "Total meeting"
        $headings[] = 'Meeting';
        $headings[] = 'Total meeting';

        return $headings;
    }

    public function formatMeetings($meetings): string
    {
        if ($meetings->isEmpty()) {
            return '-';
        }

        $formatted = $meetings->map(function ($meeting) {
            $startAt = $meeting->start_at ? Carbon::parse($meeting->start_at) : '-';
            $endAt = $meeting->end_at ? Carbon::parse($meeting->end_at) : '-';
            $time = ($meeting->start_at && $meeting->end_at)
                ? $startAt->format('H:i') . ' - ' . $endAt->format('H:i')
                : '-';

            $location = $meeting->place['type'] === 'location'
                ? ($meeting->place['value'] ?? '-')
                : ($meeting->place['value'] ?? '-');

            $result = $meeting->result ?? '-';

            return [
                'date' => $startAt->format('d/m/Y'),
                'time' => $time,
                'topic' => $meeting->topic ?? '-',
                'location' => $location,
                'result' => $result
            ];
        });

        // Group by date to add spacing between different dates
        $groupedByDate = $formatted->groupBy('date');

        $result = [];
        foreach ($groupedByDate as $date => $meetings) {
            foreach ($meetings as $meeting) {
                $result[] = sprintf(
                    "%s | %s | Topik: %s | Tempat: %s | Hasil: %s",
                    $meeting['date'],
                    $meeting['time'],
                    $meeting['topic'],
                    $meeting['location'],
                    $meeting['result']
                );
            }
            // Add extra newline after each date group except the last one
            if ($date !== $groupedByDate->keys()->last()) {
                $result[] = ''; // This adds an empty line
            }
        }

        return implode("\n", $result);
    }

    public function title(): string
    {
        $resolveDivision = Division::tryFrom($this->division)->label();
        return "Laporan Permohonan $resolveDivision";
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $sheet = $event->sheet->getDelegate();
    //             $lastColIndex = count($this->headings());
    //             $colLetter = Coordinate::stringFromColumnIndex($lastColIndex);

    //             // range dari row 1 sampai row 2 di kolom Total Data
    //             $range = "{$colLetter}1:{$colLetter}2";

    //             // terapkan bold + fill hijau muda
    //             $sheet->getStyle($range)->applyFromArray([
    //                 'font' => [
    //                     'bold' => true,
    //                 ],
    //                 'fill' => [
    //                     'fillType' => Fill::FILL_SOLID,
    //                     'startColor' => ['rgb' => 'C6EFCE'],
    //                 ],
    //             ]);
    //         },
    //     ];
    // }
}
