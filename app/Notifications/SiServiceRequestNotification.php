<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\States\InformationSystem\ApprovedKasatpel;
use App\States\InformationSystem\Disposition;
use App\States\InformationSystem\Rejected;
use App\States\InformationSystem\Replied;
use App\States\InformationSystem\RepliedKapusdatin;
use App\Models\InformationSystemRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SiServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public InformationSystemRequest $informationSystemRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct($informationSystemRequest)
    {
        $this->informationSystemRequest = $informationSystemRequest;
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
        $siRequest = $this->informationSystemRequest;
        $context = [];

        $userName = User::findOrFail($siRequest->user_id)->name;

        $context = match (get_class($siRequest->status)) {
            Disposition::class => $this->getContextForDisposition($siRequest),
            Rejected::class => $this->getContextForRejected($siRequest),
            ApprovedKasatpel::class => $this->getContextForApprovedKasatpel($siRequest),
            Replied::class => $this->getContextForReplied($siRequest),
            RepliedKapusdatin::class => $this->getContextForReplied($siRequest),
            default => [],
        };

        return [
            'requestable_type' => get_class($siRequest),
            'requestable_id' => $siRequest->id,
            'username' => $userName,
            'status' => $siRequest->status->label(),
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
