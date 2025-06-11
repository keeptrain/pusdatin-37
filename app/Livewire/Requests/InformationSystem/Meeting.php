<?php

namespace App\Livewire\Requests\InformationSystem;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Letters\Letter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class Meeting extends Component
{
    #[Locked]
    public $siRequestId;

    public $selectedResultKey;

    public $selectedOption = '';

    public $meeting = [];

    public $result = [];

    public function mount($id)
    {
        $this->siRequestId = $id;
    }

    public function updatedSelectedOption($value)
    {
        $this->reset('meeting');

        if ($value === 'in-person') {
            $this->meeting['location'] = '';
        } elseif ($value === 'online-meet') {
            $this->meeting['link'] = '';
        }
    }

    #[Computed]
    public function getMeeting()
    {
        // Ambil data InformationSystemRequest berdasarkan ID
        $letter = Letter::select('meeting')->findOrFail($this->siRequestId);
        $meetings = $letter->meeting ?? [];

        // Simpan waktu saat ini sekali saja untuk efisiensi
        $now = Carbon::now();

        // Tambahkan informasi waktu ke setiap meeting
        $meetings = array_map(function ($meeting) use ($now) {
            $meetingStart = Carbon::parse("{$meeting['date']} {$meeting['start']}");
            $meetingEnd = Carbon::parse("{$meeting['date']} {$meeting['end']}");

            // Tentukan status meeting
            if ($now->lt($meetingStart)) {
                // Meeting belum dimulai
                $diffInDays = round($now->diffInDays($meetingStart, false));
                $meeting['status'] = $diffInDays == 0 ? "Hari ini" : "$diffInDays hari lagi";
            } elseif ($now->lte($meetingEnd)) {
                // Meeting sedang berlangsung
                $meeting['status'] = "Sedang berlangsung";
            } elseif ($now->isSameDay($meetingEnd)) {
                // Meeting sudah lewat, tetapi masih di hari yang sama
                $meeting['status'] = "Hari ini tetapi sudah lewat";
            } else {
                // Meeting sudah lewat (hari lain)
                $meeting['status'] = "Sudah lewat";
            }

            // Simpan selisih waktu (opsional)
            $meeting['time_diff'] = $now->diffInDays($meetingStart, false);

            return $meeting;
        }, $meetings);

        // Urutkan meeting berdasarkan waktu terdekat dari sekarang
        uasort($meetings, function ($a, $b) use ($now) {
            $timeA = Carbon::parse("{$a['date']} {$a['end']}");
            $timeB = Carbon::parse("{$b['date']} {$b['end']}");

            // Prioritaskan meeting yang belum lewat
            if ($timeA->isFuture() && $timeB->isFuture()) {
                return $timeA <=> $timeB; // Keduanya belum lewat, urutkan berdasarkan waktu terdekat
            }
            if ($timeA->isFuture()) {
                return -1; // $a belum lewat, prioritas lebih tinggi
            }
            if ($timeB->isFuture()) {
                return 1; // $b belum lewat, prioritas lebih tinggi
            }

            // Jika keduanya sudah lewat, urutkan dari yang paling lama lewat ke yang baru saja lewat
            return $timeA <=> $timeB;
        });

        return $meetings;
    }

    public function createMeeting()
    {
        // Validasi input
        $rules = [
            'selectedOption' => 'required|in:in-person,online-meet',
            'meeting.date' => 'required|date',
            'meeting.start' => 'required|date_format:H:i',
            'meeting.end' => 'required|date_format:H:i|after:meeting.start',
        ];

        // Tambahkan validasi berdasarkan pilihan lokasi atau link menggunakan array dinamis
        $rules['meeting.' . ($this->selectedOption === 'in-person' ? 'location' : 'link')] =
            $this->selectedOption === 'in-person'
            ? 'required|string|max:255'
            : 'required|url|max:255';

        $this->validate($rules);

        // Temukan record Letter berdasarkan ID
        $siRequest = Letter::findOrFail($this->siRequestId);

        DB::transaction(function () use ($siRequest) {
            // Ambil meeting yang sudah ada (jika ada)
            $existingMeetings = $siRequest->meeting ?? [];

            // Data meeting baru
            $newMeeting = [
                'date' => $this->meeting['date'],
                'start' => $this->meeting['start'],
                'end' => $this->meeting['end'],
                'result' => null,
            ];

            // Tambahkan location atau link sesuai pilihan
            if ($this->selectedOption === 'in-person') {
                $newMeeting['location'] = $this->meeting['location'];
            } elseif ($this->selectedOption === 'online-meet') {
                $newMeeting['link'] = $this->meeting['link'];
                if (!empty($this->meeting['password'])) {
                    $newMeeting['password'] = $this->meeting['password'];
                }
            }

            // Tambahkan meeting baru ke array dengan key incremental
            $existingMeetings[] = $newMeeting;

            // Update kolom meeting di database
            $siRequest->update([
                'meeting' => $existingMeetings,
            ]);

            // Log status dan notifikasi
            $siRequest->logStatusCustom('Rencana pertemuan telah dibuat, silahkan cek detailnya.');

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Meeting berhasil dibuat',
            ]);

            $this->redirectRoute('is.meeting', ['id' => $this->siRequestId]);
        });
    }

    public function updateResultMeeting($selectedResultKey)
    {
        DB::transaction(function () use ($selectedResultKey) {
            $SiRequest = Letter::findOrFail($this->siRequestId);
            $meetings = $SiRequest->meeting;

            // Perbarui hasil meeting untuk key yang dipilih
            $meetings[$selectedResultKey]['result'] = $this->result[$selectedResultKey];

            // Simpan kembali ke database
            $SiRequest->update(['meeting' => $meetings]);

            // Reset form
            $this->reset(['selectedResultKey', 'result']);

            session()->flash('status', [
                'variant' => 'success',
                'message' => 'Hasil meeting berhasil diupdate',
            ]);

            $this->redirectRoute('is.meeting', ['id' => $this->siRequestId]);
        });
    }

    public function delete($selectedKey)
    {
        DB::transaction(function () use ($selectedKey) {
            $SiRequest = Letter::findOrFail($this->siRequestId);

            $meetings = $SiRequest->meeting;

            // Hapus elemen berdasarkan key
            unset($meetings[$selectedKey]);

            // Reset index jika kamu ingin (opsional tergantung kebutuhan)
            $meetings = array_values($meetings);

            // Simpan kembali array meeting yang telah dihapus ke model
            $SiRequest->meeting = $meetings;
            $SiRequest->save();
        });
    }
}
