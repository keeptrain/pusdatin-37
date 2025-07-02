<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Requests\InformationSystem\MeetingMail;
use App\Models\MeetingInformationSystemRequest;

class MeetingServices
{
    public function collectRecipients(array $storedRecipients): array
    {
        return collect($storedRecipients)
            ->map(fn($recipient) => $this->getRecipientsByType($recipient['type'], $recipient['id']))
            ->flatten(1)
            ->unique('email')
            ->values()
            ->all();
    }

    protected function getRecipientsByType(string $type, int $id): array
    {
        return match ($type) {
            'role' => User::role($id)
                ->get(['name', 'email'])
                ->toArray(),

            'user' => User::where('id', $id)
                ->get(['name', 'email'])
                ->toArray(),

            default => [],
        };
    }

    public function prepareEmailData($meeting, $systemRequest, $recipients): array
    {
        $date = Carbon::parse($meeting->start_at);

        $baseData = [
            'recipients' => $recipients,
            'topic' => $meeting->topic,
            'date' => $date->format('d M Y'),
            'start' => $date->format('H:i'),
            'end' => Carbon::parse($meeting->end_at)->format('H:i'),
            'place' => $meeting->place, // Changed from $this->place to $meeting->place
            'title' => $systemRequest->title,
        ];

        return $baseData;
    }

    public function sendMeetingEmails(array $data, string $emailType): void
    {
        try {
            foreach ($data['recipients'] as $recipient) {
                $emailData = array_merge($data, $recipient);
                Mail::to($recipient['email'])
                    ->send(new MeetingMail($emailData, $emailType));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send meeting email: ' . $e->getMessage());
            throw $e; // Re-throw if you want to handle in the controller
        }
    }

    public function getUpcomingMeetingsForUser(User $user, int $daysAhead = 3): Collection
    {
        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addDays($daysAhead);

        return MeetingInformationSystemRequest::with(['informationSystemRequest' => fn($query) => $query->where('user_id', $user->id)])
            ->whereHas('informationSystemRequest', fn($query) => $query->where('user_id', $user->id))
            ->whereBetween('start_at', [$startDate, $endDate])
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->get()
            ->groupBy('date')
            ->map(fn($meetings, $date) => $this->formatDateGroup($meetings, $date));
    }

    protected function formatDateGroup($meetings, string $date): array
    {
        $date = Carbon::parse($date)->locale('id');

        return [
            'date_number' => $date->day,
            'date_day' => $date->translatedFormat('l'),
            'date_month' => $date->translatedFormat('F'),
            'is_today' => $date->isToday(),
            'meetings' => $meetings->map(fn($meeting) => $this->formatMeeting($meeting))->values(),
            'has_meetings' => true
        ];
    }

    protected function formatMeeting(MeetingInformationSystemRequest $meeting): array
    {
        $startAt = Carbon::parse($meeting->start_at);
        $endAt = Carbon::parse($meeting->end_at);

        return [
            'id' => $meeting->id,
            'request_id' => $meeting->request_id,
            'topic' => $meeting->topic,
            'start' => $startAt->format('H:i'),
            'end' => $endAt->format('H:i'),
            'place' => [
                'type' => $meeting->place['type'] ?? null,
                'value' => $meeting->place['value'] ?? null,
                'password' => $meeting->password ?? null,
            ],
            'result' => $meeting->result
        ];
    }

    public function getEmptyDateSlots(int $daysAhead = 3)
    {
        return collect(range(0, $daysAhead - 1))
            ->map(fn($day) => $this->createEmptyDateSlot(now()->addDays($day)));
    }

    protected function createEmptyDateSlot(Carbon $date): array
    {
        return [
            'date_number' => $date->day,
            'date_day' => $date->locale('id')->translatedFormat('l'),
            'date_month' => $date->locale('id')->translatedFormat('F'),
            'is_today' => $date->isToday(),
            'meetings' => [],
            'has_meetings' => false
        ];
    }
}