<?php

namespace App\Notifications;

use App\Models\User;
use App\States\Replied;
use App\States\Rejected;
use App\States\Disposition;
use Illuminate\Bus\Queueable;
use App\Models\Letters\Letter;
use App\States\ApprovedKasatpel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class LetterServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Letter $letter;

    /**
     * Create a new notification instance.
     */
    public function __construct($letter)
    {
        $this->letter = $letter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $siRequest = $this->letter;
        $context = [];

        $context = match (get_class($siRequest->status)) {
            Disposition::class => $this->getContextForDisposition($siRequest),
            Rejected::class => $this->getContextForRejected($siRequest),
            ApprovedKasatpel::class => $this->getContextForApprovedKasatpel($siRequest),
            Replied::class => $this->getContextForReplied($siRequest),
            default => [],
        };

        return [
            'requestable_type' => get_class($siRequest),
            'requestable_id' => $siRequest->id,
            'status' => $siRequest->status,
            'message' => $siRequest->status->userNotificationMessage($context)
        ];
    }

    private function getContextForDisposition($siRequest): array
    {
        $userName = User::findOrFail($siRequest->user_id)->name;
        return ['responsible_person' => $userName];
    }

    private function getContextForRejected($siRequest): array
    {
        return ['active_checking' => $siRequest->active_checking];
    }

    private function getContextForApprovedKasatpel($siRequest): ?array
    {
        return ['responsible_person' => null];;
    }

    private function getContextForReplied($siRequest): ?array
    {
        if ($siRequest->need_review) {
            return ['verifikator_role' => null];
        }
        return ['verifikator_role' => $siRequest->current_division];
    }
}
