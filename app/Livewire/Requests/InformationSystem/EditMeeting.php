<?php

namespace App\Livewire\Requests\InformationSystem;

use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Models\MeetingInformationSystemRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\MeetingServices;
use App\Models\InformationSystemRequest;

class EditMeeting extends Component
{
    #[Locked]
    public int $systemRequestId;

    #[Locked]
    public string $meetingId;

    public $selectedOption;
    public $topic;
    public $place = [];
    public $date;
    public $startAt;
    public $endAt;

    public $result;

    public $pastEndDate;

    public function mount(int $id, string $meetingId)
    {
        $this->systemRequestId = $id;
        $this->meetingId = $meetingId;
        $meeting = MeetingInformationSystemRequest::where('id', $this->meetingId)->first();
        $this->topic = $meeting->topic;
        $this->place = $meeting->place;
        $this->date = Carbon::parse($meeting->start_at)->format('Y-m-d');
        $this->startAt = Carbon::parse($meeting->start_at)->format('H:i');
        $this->endAt = Carbon::parse($meeting->end_at)->format('H:i');
        $this->result = $meeting->result;
        $this->pastEndDate = now()->gt($meeting->end_at);
    }

    public function render()
    {
        return view('livewire.requests.information-system.edit-meeting');
    }

    public function update(MeetingServices $meetingServices)
    {
        $rules = [
            'topic' => 'required|string|max:150',
            'place.type' => 'required|string|in:location,link',
            'date' => 'required|date',
            'startAt' => 'required|date_format:H:i',
            'endAt' => 'required|date_format:H:i|after:startAt',
            'result' => 'nullable|string',
        ];

        if ($this->place['type'] === 'location') {
            $rules['place.value'] = 'required|string|max:255';
        } elseif ($this->place['type'] === 'link') {
            $rules['place.value'] = 'required|url|max:255';
            $rules['place.password'] = 'nullable|string|max:40';
        }

        $this->validate($rules);

        DB::transaction(function () use ($meetingServices) {
            $meeting = MeetingInformationSystemRequest::findOrFail($this->meetingId);
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);

            $place = [
                'type' => $this->place['type'],
                'value' => $this->place['value'],
            ];

            if ($this->place['type'] === 'link') {
                $place['password'] = $this->place['password'] ?? null;
            } else {
                unset($place['password']);
            }

            $meeting->update([
                'topic' => $this->topic,
                'place' => $place,
                'start_at' => Carbon::parse("{$this->date} {$this->startAt}"),
                'end_at' => Carbon::parse("{$this->date} {$this->endAt}"),
                'result' => $this->result,
            ]);

            $recipients = $meetingServices->collectRecipients($meeting->recipients);

            $emailData = $meetingServices->prepareEmailData($meeting, $systemRequest, $recipients);

            DB::afterCommit(function () use ($meetingServices, $emailData) {
                $meetingServices->sendMeetingEmails($emailData, 'update');
            });

        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Meeting berhasil diperbarui',
        ]);

        $this->redirectRoute('is.meeting.edit', ['id' => $this->systemRequestId, 'meetingId' => $this->meetingId]);

    }
}
