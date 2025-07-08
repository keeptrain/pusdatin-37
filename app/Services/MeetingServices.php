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
            'place' => $meeting->place,
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
            throw $e;
        }
    }

    public function getUserMeetings(User $user, int $daysAhead = 3): Collection
    {
        $meetings = $this->fetchUserMeetings($user, $daysAhead);
        return $this->prepareMeetingTimeline($meetings, $daysAhead);
    }

    public function getAdminMeetings(User $admin, int $daysAhead = 3): Collection
    {
        $meetings = $this->fetchAdminMeetings($admin, $daysAhead);
        return $this->prepareMeetingTimeline($meetings, $daysAhead);
    }

    protected function fetchUserMeetings(User $user, int $daysAhead): Collection
    {
        return MeetingInformationSystemRequest::with(['informationSystemRequest' => fn($q) => $q->where('user_id', $user->id)])
            ->whereHas('informationSystemRequest', fn($q) => $q->where('user_id', $user->id))
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->get();
    }

    protected function fetchAdminMeetings(User $admin, int $daysAhead): Collection
    {
        $roleId = $admin->currentUserRoleId();

        return MeetingInformationSystemRequest::where('start_at', '>=', now()) // Get all future meetings
            ->get()
            ->filter(fn($meeting) => $this->isMeetingRelevant($meeting, $roleId));
    }

    public function prepareMeetingTimeline(Collection $meetings, int $daysAhead = 3): Collection
    {
        $groupedMeetings = $this->groupAndFormatMeetings($meetings);
        $attentionSlots = $this->generateEmptySlots($daysAhead);

        // Mark attention period dates
        $attentionPeriodEnd = now()->addDays($daysAhead);

        // Merge with empty slots for attention period
        $attentionPeriod = $this->mergeTimeline($groupedMeetings, $attentionSlots)
            ->map(function ($day) use ($attentionPeriodEnd) {
                $date = Carbon::parse($day['date_day']);
                $day['is_attention_period'] = $date->lte($attentionPeriodEnd);
                return $day;
            });

        // Add other meetings beyond attention period
        $otherMeetings = $groupedMeetings->reject(function ($day) use ($attentionPeriodEnd) {
            return Carbon::parse($day['date_day'])->lte($attentionPeriodEnd);
        });

        return $attentionPeriod->concat($otherMeetings->values())
            ->sortBy(fn($day) => Carbon::parse($day['date_day'])->timestamp)
            ->values();
    }

    protected function isMeetingRelevant(MeetingInformationSystemRequest $meeting, int $roleId): bool
    {
        return collect($meeting->recipients ?? [])->contains(
            fn($recipient) => $recipient['type'] === 'role' && $recipient['id'] === $roleId
        );
    }

    protected function getDateRange(int $daysAhead): array
    {
        $start = Carbon::today();
        return [$start, $start->copy()->addDays($daysAhead)];
    }

    protected function groupAndFormatMeetings(Collection $meetings): Collection
    {
        return $meetings->groupBy(fn($m) => Carbon::parse($m->start_at)->format('Y-m-d'))
            ->map(fn($dayMeetings, $date) => $this->formatDayGroup($dayMeetings, $date));
    }

    protected function formatDayGroup(Collection $meetings, string $date): array
    {
        $dateObj = Carbon::parse($date);

        return [
            'date_number' => $dateObj->day,
            'date_day' => $dateObj->translatedFormat('l'),
            'date_month' => $dateObj->translatedFormat('F'),
            'is_today' => $dateObj->isToday(),
            'meetings' => $meetings->map(fn($m) => $this->formatMeeting($m))->values(),
            'has_meetings' => true
        ];
    }

    protected function formatMeeting(MeetingInformationSystemRequest $meeting): array
    {
        return [
            'id' => $meeting->id,
            'request_id' => $meeting->request_id,
            'topic' => $meeting->topic,
            'time' => $this->formatTimeRange($meeting->start_at, $meeting->end_at),
            'place' => [
                'type' => $meeting->place['type'] ?? null,
                'value' => $meeting->place['value'] ?? null,
                'password' => $meeting->place['password'] ?? null,
            ],
            'result' => $meeting->result,
            'recipients' => $meeting->recipients ?? []
        ];
    }

    protected function formatTimeRange(string $start, string $end): string
    {
        return Carbon::parse($start)->format('H:i') . ' - ' . Carbon::parse($end)->format('H:i');
    }

    protected function generateEmptySlots(int $daysAhead): Collection
    {
        return collect(range(0, $daysAhead - 1))
            ->map(fn($day) => $this->createEmptySlot(now()->addDays($day)));
    }

    protected function createEmptySlot(Carbon $date): array
    {
        return [
            'date_number' => $date->day,
            'date_day' => $date->translatedFormat('l'),
            'date_month' => $date->translatedFormat('F'),
            'is_today' => $date->isToday(),
            'meetings' => [],
            'has_meetings' => false
        ];
    }

    protected function mergeTimeline(Collection $meetings, Collection $slots): Collection
    {
        return $slots->map(function ($slot) use ($meetings) {
            return $meetings->firstWhere('date_day', $slot['date_day']) ?? $slot;
        });
    }

    public function getTodayMeetingsCount(Collection $timeline): int
    {
        return $timeline->where('is_today', true)
            ->sum(fn($day) => count($day['meetings']));
    }
}