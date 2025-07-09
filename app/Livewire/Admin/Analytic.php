<?php

namespace App\Livewire\Admin;

use App\Exports\InformationSystemExport;
use Livewire\Component;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultipleSheetExport;
use App\Exports\PublicRelationExport;
use App\Enums\Division;
use Barryvdh\DomPDF\Facade\Pdf;

class Analytic extends Component
{
    public $startAt = '';
    public $endAt = '';
    public $service = '';
    public $status = '';

    public $statusOptions = [
        'all' => 'Semua Status',
        'in_process' => 'Dalam Proses',
        // 'pending' => 'Permohonan Masuk',
        // 'disposition' => 'Disposisi',
        // 'process' => 'Proses',
        // 'replied' => 'Revisi Kasatpel',
        // 'approved_kasatpel' => 'Disetujui Kasatpel',
        // 'replied_kapusdatin' => 'Revisi Kapusdatin',
        // 'approved_kapusdatin' => 'Disetujui Kapusdatin',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak',
    ];

    public $statusOptionsPr = [
        'all' => 'All',
        'antrian_promkes' => 'Antrian Promkes',
        'kurasi_promkes' => 'Kurasi Promkes',
        'antrian_pusdatin' => 'Antrian Pusdatin',
        'proses_pusdatin' => 'Proses Pusdatin',
        'completed' => 'Completed',
    ];

    public $showModal = false;

    #[Title('Analitik')]
    public function render()
    {
        return view('livewire.admin.analytic');
    }

    public function exportAsPdf($startAt = null, $endAt = null)
    {
        $currentDivisionUser = auth()->user()->currentUserRoleId();
        $fileName = "Daftar Permohonan - " . now() . ".pdf";

        $export = new InformationSystemExport($currentDivisionUser, $this->startAt, $this->endAt, $this->status);

        $collection = $export->collection();
        $headings = $export->headings(isExcel: false);
        $title = $export->title();

        $pdf = Pdf::loadView('components.exports.pdf.information-system', compact('collection', 'title', 'headings'))->setPaper('a3', 'landscape');

        return response()->streamDownload(function () use ($pdf, $fileName, $startAt, $endAt) {
            echo $pdf->stream($fileName);
        }, $fileName);
    }

    public function exportAsExcel()
    {
        $currentDivisionUser = auth()->user()->currentUserRoleId();
        $fileName = "Daftar Permohonan - " . now() . ".xlsx";

        $exportBasedRole = match ($currentDivisionUser) {
            Division::HEAD_ID->value => $this->exportFilterService($currentDivisionUser, $fileName),
            Division::SI_ID->value, Division::DATA_ID->value => Excel::download(new InformationSystemExport($currentDivisionUser, $this->startAt, $this->endAt), $fileName),
            Division::PR_ID->value => Excel::download(new PublicRelationExport($currentDivisionUser, $this->startAt, $this->endAt), $fileName),
            default => null,
        };

        return $exportBasedRole;
    }

    protected function exportFilterService($currentDivisionUser, $fileName)
    {
        if ($this->service !== 'all') {
            return match ($this->service) {
                'si' => Excel::download(new InformationSystemExport($currentDivisionUser, $this->startAt, $this->endAt), $fileName),
                'pr' => Excel::download(new PublicRelationExport($currentDivisionUser, $this->startAt, $this->endAt), $fileName),
                default => Excel::download(new MultipleSheetExport($currentDivisionUser, $this->startAt, $this->endAt), $fileName),
            };
        }
    }

    public function customFilters()
    {
        $rules = [
            'startAt' => 'required',
            'endAt' => 'required|after_or_equal:startAt',
            'status' => 'required|string',
        ];

        $messages = [
            'startAt.required' => 'Tanggal awal wajib diisi.',
            'endAt.required' => 'Tanggal akhir wajib diisi.',
            'endAt.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal.',
            'status.required' => 'Status wajib diplih.',
        ];

        if (auth()->user()->hasRole('head_verifier')) {
            $rules['service'] = 'required|string|in:all,si,pr';
            $messages['service.required'] = 'Jenis layanan wajib diplih.';
        }

        $this->validate($rules, $messages);

        $this->showModal = true;
    }
}