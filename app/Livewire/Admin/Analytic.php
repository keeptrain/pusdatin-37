<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Analytic extends Component
{
    public $start_date;
    public $end_date;
    public $status;
    public $statusOptions = [
        'all'                => 'All Status',
        'disposition'        => 'Disposition',
        'process'            => 'Process',
        'replied'            => 'Replied',
        'approved_kasatpel'  => 'Approved Kasatpel',
        'replied_kapusdatin' => 'Replied Kapusdatin',
        'approved_kapusdatin' => 'Approved Kapusdatin',
        'rejected'           => 'Rejected',

    ];
    public $statusOptionsPr = [
        'all' => 'All',
        'antrian_promkes' => 'Antrian Promkes',
        'kurasi_promkes' => 'Kurasi Promkes',
        'antrian_pusdatin' => 'Antrian Pusdatin',
        'proses_pusdatin' => 'Proses Pusdatin',
        'completed' => 'Completed',
    ];

    // Kontrol modal
    public $showModal = false;
    public function render()
    {
        return view('livewire.admin.analytic');
    }
    public function applyFilters()
    {
        // $this->validate([
        //     'start_date' => 'nullable|date',
        //     'end_date'   => 'nullable|date|after_or_equal:start_date',
        // 'status'     => 'nullable|string|in:disposition,process,replied,approved_kasatpel,replied_kapusdatin,approved_kapusdatin,rejected,all',
        // ]);

        $this->showModal = true;
    }

    /**
     * Reset filter + tutup modal
     */
    public function resetFilters()
    {
        $this->start_date = null;
        $this->end_date   = null;
        $this->status     = null;
        $this->showModal  = false;
    }
}
