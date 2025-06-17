<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class Analytic extends Component
{
    public $start_date;
    public $end_date;
    public $status;
    public $source; // Menambah properti source untuk memilih data (letter/pr)

    public $statusOptions = [
        'all'                => 'Semua Status',
        'pending'            => 'Permohonan Masuk',
        'disposition'        => 'Disposisi',
        'process'            => 'Proses',
        'replied'            => 'Revisi Kasatpel',
        'approved_kasatpel'  => 'Disetujui Kasatpel',
        'replied_kapusdatin' => 'Revisi Kapusdatin',
        'approved_kapusdatin' => 'Disetujui Kapusdatin',
        'rejected'           => 'Ditolak',
    ];

    public $statusOptionsPr = [
        'all'               => 'All',
        'antrian_promkes'   => 'Antrian Promkes',
        'kurasi_promkes'    => 'Kurasi Promkes',
        'antrian_pusdatin'  => 'Antrian Pusdatin',
        'proses_pusdatin'   => 'Proses Pusdatin',
        'completed'         => 'Completed',
    ];


    public $showModal = false;

    public function exportHeadVerifier()
    {
        if (auth()->user()->hasRole('head_verifier')) {

            // Ambil data dari Letter atau PR berdasarkan source
            if ($this->source === 'letter') {
                $query = Letter::with('user');
            } elseif ($this->source === 'pr') {
                $query = PublicRelationRequest::with('user');
            } else {
                return; // Jika tidak memilih source, tidak lakukan apa-apa
            }

            // Apply filter berdasarkan start_date dan end_date
            if ($this->start_date) {
                $query->whereDate('created_at', '>=', $this->start_date);
            }

            if ($this->end_date) {
                $query->whereDate('created_at', '<=', $this->end_date);
            }

            // Filter berdasarkan status (jika ada)
            if ($this->status && $this->status !== 'all') {
                if ($this->source === 'letter') {
                    $stateClass = Letter::resolveStatusClassFromString($this->status);
                    $query->whereState('status', $stateClass);
                } else if ($this->source === 'pr') {
                    $stateClassPr = PublicRelationRequest::resolveStatusClassFromString($this->status);
                    $query->whereState('status', $stateClassPr);
                }
            }

            // Ambil data yang sudah difilter
            $data = $query->get();

            // Siapkan data untuk view export
            $exportData = [
                'data' => $data,
                'startDate' => $this->start_date,
                'endDate' => $this->end_date,
                'status' => $this->status,
            ];

            // Trigger modal export atau download
            $this->showModal = true; // Anda bisa menambahkan logic export di sini
        }
    }

    // Fungsi apply filter untuk role lainnya
    public function applyFilters()
    {
        $this->showModal = true;
    }

    // Fungsi reset filter
    public function resetFilters()
    {
        $this->start_date = null;
        $this->end_date   = null;
        $this->status     = null;
        $this->source     = null; // Reset source
        $this->showModal  = false;
    }

    public function render()
    {
        return view('livewire.admin.analytic');
    }
}
