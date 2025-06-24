<?php

namespace App\Livewire\Requests\InformationSystem;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\InformationSystemRequest;
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

    public $resultUpdate = [];

    public function mount($id)
    {
        $this->siRequestId = $id;

        foreach ($this->getMeeting as $key => $meeting) {
            if (!empty($meeting['result'])) {
                $this->result[$key] = $meeting['result'];
            }
        }
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
        // Get information system request by id
        $siRequest = InformationSystemRequest::select('meetings')->findOrFail($this->siRequestId);
        $meetings = $siRequest->meetings ?? [];

        // Get current time
        $now = Carbon::now();

        // Add time information to each meeting
        $meetings = array_map(function ($meetings) use ($now) {
            $meetingStart = Carbon::parse("{$meetings['date']} {$meetings['start']}");
            $meetingEnd = Carbon::parse("{$meetings['date']} {$meetings['end']}");

            // Determine meeting status
            if ($now->lt($meetingStart)) {
                // Meeting has not started
                $diffInDays = round($now->diffInDays($meetingStart, false));
                $meetings['status'] = $diffInDays == 0 ? "Hari ini" : "$diffInDays hari lagi";
            } elseif ($now->lte($meetingEnd)) {
                // Meeting is ongoing
                $meetings['status'] = "Sedang berlangsung";
            } elseif ($now->isSameDay($meetingEnd)) {
                // Meeting is today but already passed
                $meetings['status'] = "Hari ini tetapi sudah lewat";
            } else {
                // Meeting is passed (other day)
                $meetings['status'] = "Sudah lewat";
            }

            // Save time difference (optional)
            $meetings['time_diff'] = $now->diffInDays($meetingStart, false);

            return $meetings;
        }, $meetings);

        // Sort meeting based on the closest time from now
        uasort($meetings, function ($a, $b) use ($now) {
            $timeA = Carbon::parse("{$a['date']} {$a['end']}");
            $timeB = Carbon::parse("{$b['date']} {$b['end']}");

            // Prioritize meeting that has not started
            if ($timeA->isFuture() && $timeB->isFuture()) {
                return $timeA <=> $timeB; // Both have not started, sort based on closest time
            }
            if ($timeA->isFuture()) {
                return -1; // $a has not started, prioritize higher
            }
            if ($timeB->isFuture()) {
                return 1; // $b has not started, prioritize higher
            }

            // If both have passed, sort from the oldest to the most recent
            return $timeA <=> $timeB;
        });

        return $meetings;
    }

    public function create()
    {
        // Validate input
        $rules = [
            'selectedOption' => 'required|in:in-person,online-meet',
            'meeting.date' => 'required|date',
            'meeting.start' => 'required|date_format:H:i',
            'meeting.end' => 'required|date_format:H:i|after:meeting.start',
        ];

        // Add validation based on location or link selection using dynamic array
        $rules['meeting.' . ($this->selectedOption === 'in-person' ? 'location' : 'link')] =
            $this->selectedOption === 'in-person'
            ? 'required|string|max:255'
            : 'required|url|max:255';

        $this->validate($rules);

        // Find record InformationSystemRequest based on ID
        $siRequest = InformationSystemRequest::findOrFail($this->siRequestId);

        DB::transaction(function () use ($siRequest) {
            // Get existing meetings (if any)
            $existingMeetings = $siRequest->meetings ?? [];

            // Data meeting baru
            $newMeeting = [
                'date' => $this->meeting['date'],
                'start' => $this->meeting['start'],
                'end' => $this->meeting['end'],
                'result' => null,
            ];

            // Add location or link based on selection
            if ($this->selectedOption === 'in-person') {
                $newMeeting['location'] = $this->meeting['location'];
            } elseif ($this->selectedOption === 'online-meet') {
                $newMeeting['link'] = $this->meeting['link'];
                if (!empty($this->meeting['password'])) {
                    $newMeeting['password'] = $this->meeting['password'];
                }
            }

            // Add new meeting to array with incremental key
            $existingMeetings[] = $newMeeting;

            // Update meeting column in database
            $siRequest->update([
                'meetings' => $existingMeetings,
            ]);

            // Log status dan notifikasi
            // $siRequest->logStatusCustom('Meeting telah dibuat, silahkan cek detailnya.');

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
            $SiRequest = InformationSystemRequest::findOrFail($this->siRequestId);
            $meetings = $SiRequest->meetings;

            // Update meeting result for selected key
            $meetings[$selectedResultKey]['result'] = $this->result[$selectedResultKey];

            // Save back to database
            $SiRequest->update(['meetings' => $meetings]);

        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Hasil meeting berhasil diupdate',
        ]);

        $this->dispatch('modal-close', name: "edit-meeting-{$selectedResultKey}-modal");
    }

    public function delete($selectedKey)
    {
        DB::transaction(function () use ($selectedKey) {
            $SiRequest = InformationSystemRequest::findOrFail($this->siRequestId);

            $meetings = $SiRequest->meetings;

            // Remove element based on key
            unset($meetings[$selectedKey]);

            // Reset index if you want (optional depending on needs)
            $meetings = array_values($meetings);

            // Save back array meeting that has been deleted to model
            $SiRequest->meetings = $meetings;
            $SiRequest->save();
        });
    }
}
