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
        // systemRequest as InformationSystemRequest
        $systemRequest = $this->informationSystemRequest;
        $context = [];

        $userName = User::findOrFail($systemRequest->user_id)->name;

        $context = match (get_class($systemRequest->status)) {
            Disposition::class => $this->getContextForDisposition($systemRequest),
            Rejected::class => $this->getContextForRejected($systemRequest),
            ApprovedKasatpel::class => $this->getContextForApprovedKasatpel($systemRequest),
            Replied::class => $this->getContextForReplied($systemRequest),
            RepliedKapusdatin::class => $this->getContextForReplied($systemRequest),
            default => [],
        };

        return [
            'requestable_type' => get_class($systemRequest),
            'requestable_id' => $systemRequest->id,
            'username' => $userName,
            'status' => $systemRequest->status->label(),
            'message' => $systemRequest->status->userNotificationMessage($context)
        ];
    }

    private function getContextForDisposition($systemRequest): array
    {
        $userName = User::findOrFail($systemRequest->user_id)->name;
        return ['responsible_person' => $userName];
    }

    private function getContextForRejected($systemRequest): array
    {
        return ['active_checking' => $systemRequest->active_checking];
    }

    private function getContextForApprovedKasatpel($systemRequest): ?array
    {
        return ['responsible_person' => null];
        ;
    }

    private function getContextForReplied($systemRequest): ?array
    {
        if ($systemRequest->need_review) {
            return ['verifikator_role' => null];
        }
        return ['verifikator_role' => $systemRequest->current_division];
    }
}
