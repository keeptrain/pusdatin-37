<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Enums\Division;
use App\Models\InformationSystemRequest;
use App\Models\MeetingInformationSystemRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Services\MeetingServices;

class Meeting extends Component
{
    #[Locked]
    public $systemRequestId;
    public $selectedOption = '';
    public $topic = '';
    public $place = [];
    public $date = '';
    public $startAt = '';
    public $endAt = '';
    public $recipients = [];
    public $result = [];

    public $meetings;

    public function mount(int $id)
    {
        $this->systemRequestId = $id;
        $this->meetings = MeetingInformationSystemRequest::where('request_id', $this->systemRequestId)->get();
    }

    public function updatedSelectedOption($value)
    {
        // Reset nilai place
        $this->place = [];

        if ($value === 'in-person') {
            $this->place['type'] = 'location';
            $this->place['value'] = '';
        } elseif ($value === 'online-meet') {
            $this->place['type'] = 'link';
            $this->place['value'] = '';
            $this->place['password'] = '';
        }
    }

    public function handleRecipients(InformationSystemRequest $request): array
    {
        return array_map(function ($type) use ($request) {
            return match ($type) {
                'kapusdatin' => ['type' => 'role', 'id' => Division::HEAD_ID],
                'kasatpel' => ['type' => 'role', 'id' => $request->current_division],
                'user' => ['type' => 'user', 'id' => $request->user_id],
                default => null,
            };
        }, $this->recipients);
    }

    public function create(MeetingServices $meetingServices)
    {
        $rules = [
            'topic' => 'required|string|max:100',
            'place' => 'required|array',
            'place.type' => 'required|string|in:location,link',
            'date' => 'required|date',
            'startAt' => 'required|date_format:H:i',
            'endAt' => 'required|date_format:H:i|after:startAt',
            'recipients' => 'required|array|in:kapusdatin,kasatpel,user',
        ];

        // Add validation based on location or link selection using dynamic array
        if ($this->place['type'] === 'location') {
            $rules['place.value'] = 'required|string|max:255';
        } elseif ($this->place['type'] === 'link') {
            $rules['place.value'] = 'required|url|max:255';
            $rules['place.password'] = 'nullable|string|max:255';
        }

        $this->validate($rules);

        DB::transaction(function () use ($meetingServices) {
            // Get information system request
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);

            $meeting = MeetingInformationSystemRequest::create([
                'id' => Str::uuid(),
                'request_id' => $this->systemRequestId,
                'topic' => $this->topic,
                'place' => $this->place,
                'start_at' => Carbon::parse("{$this->date} {$this->startAt}"),
                'end_at' => Carbon::parse("{$this->date} {$this->endAt}"),
                'recipients' => $this->handleRecipients($systemRequest),
            ]);

            // Collect recipients
            $recipients = $meetingServices->collectRecipients($meeting->recipients);

            // Prepare email data
            $emailData = $meetingServices->prepareEmailData($meeting, $systemRequest, $recipients);

            DB::afterCommit(function () use ($meetingServices, $emailData) {
                $meetingServices->sendMeetingEmails($emailData, 'create');
            });
        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Meeting berhasil dibuat',
        ]);

        // $this->redirectRoute('is.meeting', ['id' => $this->systemRequestId]);
    }

    #[Computed]
    public function getMeetings()
    {
        // Get all meetings based on request_id
        $meetings = $this->meetings;

        // If no meetings, return empty array
        if ($meetings->isEmpty()) {
            return [];
        }

        // Get current time
        $now = Carbon::now();

        // Add time and status information to each meeting
        $meetings = $meetings->map(function ($meeting) use ($now) {
            // Parse start and end time of meeting
            $meetingStart = Carbon::parse($meeting->start_at);
            $meetingEnd = Carbon::parse($meeting->end_at);

            $place = $meeting->place;

            // Determine meeting status
            if ($now->lt($meetingStart)) {
                // Meeting has not started
                $diffInDays = round($now->diffInDays($meetingStart, false));
                $status = $diffInDays == 0 ? "Hari ini" : "$diffInDays hari lagi";
            } elseif ($now->lte($meetingEnd)) {
                // Meeting is ongoing
                $status = "Sedang berlangsung";
            } elseif ($now->isSameDay($meetingEnd)) {
                // Meeting today but already passed
                $status = "Hari ini tetapi sudah lewat";
            } else {
                // Meeting already passed (other day)
                $status = "Sudah lewat";
            }

            // Add additional attributes to meeting
            return [
                'id' => $meeting->id,
                'topic' => $meeting->topic,
                'place' => $place,
                'date' => Carbon::parse($meeting->start_at)->format('d M Y'),
                'start_at' => Carbon::parse($meeting->start_at)->format('H:i'),
                'end_at' => Carbon::parse($meeting->end_at)->format('H:i'),
                'status' => $status,
                'result' => $meeting->result,
                'time_diff' => $now->diffInDays($meetingStart, false),
                'pastEndDate' => $now->gt($meetingEnd),
            ];
        });

        // Sort meetings based on the nearest time
        $sortedMeetings = $meetings->sortBy(function ($meeting) use ($now) {
            $meetingEnd = Carbon::parse($meeting['date']);

            // Prioritize meetings that have not started
            if ($meetingEnd->isFuture()) {
                return $meetingEnd->getTimestamp(); // Use timestamp for sorting
            }

            // If the meeting has passed, sort from the most recent
            return -$meetingEnd->getTimestamp(); // Negatif timestamp untuk meeting lama
        });

        return $sortedMeetings->values()->all();
    }

    #[Computed]
    public function isMeetingPastEndDate()
    {
        return now()->gt($this->meetings->end_at);
    }

    public function updateResultMeeting($selectedId)
    {
        $meeting = MeetingInformationSystemRequest::findOrFail($selectedId);

        $this->validate([
            "result.{$selectedId}" => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($selectedId, $meeting) {
            $meeting->update([
                'result' => $this->result[$selectedId],
            ]);

            $meeting->save();
        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Hasil meeting berhasil diupdate',
        ]);

        $this->redirectRoute('is.meeting', ['id' => $this->systemRequestId]);
    }

    public function delete(?string $selectedId, MeetingServices $meetingServices)
    {
        DB::transaction(function () use ($selectedId, $meetingServices) {
            $meeting = MeetingInformationSystemRequest::findOrFail($selectedId);

            $systemRequest = InformationSystemRequest::findOrFail($meeting->request_id);

            $recipients = $meetingServices->collectRecipients($meeting->recipients);

            $emailData = $meetingServices->prepareEmailData($meeting, $systemRequest, $recipients);

            $meeting->delete();

            DB::afterCommit(function () use ($meeting, $meetingServices, $emailData) {
                $meetingServices->sendMeetingEmails($emailData, 'delete');
            });
        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Meeting berhasil dihapus',
        ]);

        $this->redirectRoute('is.meeting', ['id' => $this->systemRequestId]);
    }
}
