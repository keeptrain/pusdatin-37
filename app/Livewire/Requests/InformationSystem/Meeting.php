<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Enums\Division;
use App\Models\InformationSystemRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;
use App\Mail\Requests\InformationSystem\MeetingMail;

class Meeting extends Component
{
    #[Locked]
    public $systemRequestId;

    public $selectedOption = '';

    public $meeting = [];

    public $result = [];

    public $selectedResultKey;

    public $resultUpdate = [];

    public function mount($id)
    {
        $this->systemRequestId = $id;

        foreach ($this->getMeeting as $meeting) {
            if (!empty($meeting['result'])) {
                $this->result[$meeting['id']] = $meeting['result'];
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

    public function collectRecipientIds($meetingRecipients, $siDataRequest)
    {
        $recipients = [];

        foreach ($meetingRecipients as $recipientType) {
            switch ($recipientType) {
                case 'kapusdatin':
                    // Get all users with role based on division
                    $kapusdatinUsers = User::role(Division::HEAD_ID)->get(['name', 'email']);
                    foreach ($kapusdatinUsers as $user) {
                        $recipients[] = ['name' => $user->name, 'email' => $user->email];
                    }
                    break;

                case 'kasatpel':
                    // Get all users with role based on division
                    $currentDivision = $siDataRequest->current_division;
                    $kasatpelUsers = User::role($currentDivision)->get(['name', 'email']);
                    foreach ($kasatpelUsers as $user) {
                        $recipients[] = ['name' => $user->name, 'email' => $user->email];
                    }
                    break;

                case 'user':
                    // Get userId
                    $userId = $siDataRequest->user_id;
                    if ($userId) {
                        $user = User::findOrFail($userId);
                        $recipients[] = ['name' => $user->name, 'email' => $user->email];
                    }
                    break;
            }
        }

        // Remove duplicate based on email
        $uniqueRecipients = [];
        foreach ($recipients as $recipient) {
            $uniqueRecipients[$recipient['email']] = $recipient;
        }

        return array_values($uniqueRecipients); // array indexed
    }

    public function sendMail(array $data, string $emailType)
    {
        try {
            foreach ($data['recipients'] as $recipient) {
                $emailData = array_merge($data, $recipient);
                // unset($emailData['result']);
                // Mail::to($recipient['email'])->send(new MeetingMail($emailData, $emailType));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send mail: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $rules = [
            'selectedOption' => 'required|in:in-person,online-meet',
            'meeting.topic' => 'required|string|max:150',
            'meeting.date' => 'required|date',
            'meeting.start' => 'required|date_format:H:i',
            'meeting.end' => 'required|date_format:H:i|after:meeting.start',
            'meeting.recipients' => 'required|array|in:kapusdatin,kasatpel,user',
        ];

        // Add validation based on location or link selection using dynamic array
        $rules['meeting.' . ($this->selectedOption === 'in-person' ? 'location' : 'link')] =
            $this->selectedOption === 'in-person'
            ? 'required|string|max:255'
            : 'required|url|max:255';

        $this->validate($rules);

        // Find record InformationSystemRequest based on ID
        $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);

        DB::transaction(function () use ($systemRequest) {
            // Get existing meetings (if any)
            $existingMeetings = $systemRequest->meetings ?? [];

            // Data meeting baru
            $newMeeting = [
                'id' => Str::uuid(),
                'topic' => $this->meeting['topic'],
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
            $systemRequest->update([
                'meetings' => $existingMeetings,
            ]);

            // Collect recipients
            $recipients = $this->collectRecipientIds($this->meeting['recipients'], $systemRequest);

            // Merge data email with recipients, new meeting, and title
            $dataEmail = array_merge(['recipients' => $recipients], $newMeeting, ['title' => $systemRequest->title]);

            // Get end of day from data new meeting date
            $diffInDay = Carbon::parse($dataEmail['date'])->endOfDay();
            Cache::put("email:meeting-{$dataEmail['id']}", $dataEmail, $diffInDay);

            DB::afterCommit(function () use ($dataEmail) {
                $this->sendMail($dataEmail, 'create');
            });
        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Meeting berhasil dibuat',
        ]);

        $this->redirectRoute('is.meeting', ['id' => $this->systemRequestId]);
    }

    #[Computed]
    public function getMeeting()
    {
        // Get information system request by id
        $systemRequest = InformationSystemRequest::select('meetings')->findOrFail($this->systemRequestId);
        $meetings = $systemRequest->meetings ?? [];

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

    public function updateResultMeeting($selectedResultKey)
    {
        DB::transaction(function () use ($selectedResultKey) {
            // Find data InformationSystemRequest
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);
            $meetings = $systemRequest->meetings;

            // // Check if $meetings is an array
            // if (!is_array($meetings)) {
            //     throw new \Exception('Meetings data is not an array.');
            // }

            // // Validate $selectedResultKey
            // if (!is_string($selectedResultKey) || !isset($meetings[$selectedResultKey])) {
            //     throw new \InvalidArgumentException('Invalid or missing selected result key.');
            // }

            // Update field 'result' for selected meeting
            $meetings[$selectedResultKey]['result'] = $this->result[$selectedResultKey];

            // Save back to database without changing the array structure
            $systemRequest->update(['meetings' => $meetings]);
        });

        session()->flash('status', [
            'variant' => 'success',
            'message' => 'Hasil meeting berhasil diupdate',
        ]);

        $this->dispatch('modal-close', name: "edit-meeting-{$selectedResultKey}-modal");
    }

    public function delete(string $selectedKey)
    {
        DB::transaction(function () use ($selectedKey) {
            $systemRequest = InformationSystemRequest::findOrFail($this->systemRequestId);

            // Remove quotes and slashes from selected key
            $selectedKey = trim($selectedKey, '"');
            $selectedKey = stripslashes($selectedKey);

            // Get meeting data from cache
            $getMeetingData = Cache::get("email:meeting-{$selectedKey}");
            $meetingId = $getMeetingData['id']->toString();

            $filteredMeeting = array_filter(
                $systemRequest->meetings,
                fn($meeting) => Uuid::fromString($meeting['id'])->toString() !== $meetingId
            );

            $systemRequest->update([
                'meetings' => $filteredMeeting,
            ]);

            $systemRequest->save();

            DB::afterCommit(function () use ($selectedKey, $getMeetingData) {
                $this->sendMail($getMeetingData, 'delete');

                // Delete cache after send mail based on id (uuid)
                Cache::forget("email:meeting-{$selectedKey}");
            });
        });
    }
}
