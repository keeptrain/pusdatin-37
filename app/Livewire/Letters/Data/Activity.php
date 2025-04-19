<?php

namespace App\Livewire\Letters\Data;

use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\Letters\RequestStatusTrack;
use Livewire\WithPagination;

class Activity extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $requestStatusTrack;

    public int $letterId;

    public $activity;


    public function mount(int $id)
    {
        $this->letterId = $id;
        $this->activity = Letter::with('requestStatusTrack')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.letters.data.activity', [
            'data' =>  $this->getActivitiesProperty(),
        ]);
    }

    public function getLetterProperty()
    {
        return Letter::findOrFail($this->letterId);
    }

    public function getActivitiesProperty()
    {
        return Letter::query()
        ->where('id', $this->letterId) // Memastikan kita hanya mengambil Letter dengan ID yang sesuai
        ->with(['requestStatusTrack' => function ($query) {
            $query->orderBy('created_at', 'desc'); // Mengurutkan status track berdasarkan waktu pembuatan
        }])
        ->firstOrFail() // Mengambil model Letter beserta relasi, gagal jika tidak ditemukan
        ->requestStatusTrack;
    }

}
