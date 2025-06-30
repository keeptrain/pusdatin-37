<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Requests\InformationSystem\MeetingMail;

class MeetingServices
{
    public function collectRecipients(array $storedRecipients): array
    {
        return collect($storedRecipients)
            ->map(function ($recipient) {
                return $this->getRecipientsByType($recipient['type'], $recipient['id']);
            })
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
}