<?php

namespace App\Notifications;

use App\Mail\Requests\InformationSystem\RevisionMail;
use App\States\InformationSystem\Replied;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\Log;

class RevisionRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public InformationSystemRequest $informationSystemRequest, public ?array $data = null)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        if (!$this->informationSystemRequest->status instanceof Replied) {
            return;
        }

        try {
            return new RevisionMail($this->data, 'first-time')->to($notifiable->email);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $systemRequest = $this->informationSystemRequest;
        $context = [];

        $userName = $systemRequest->user->name;

        $context = $this->getContextForRevision($systemRequest);

        return [
            'requestable_type' => InformationSystemRequest::class,
            'requestable_id' => $systemRequest->id,
            'username' => $userName,
            'status' => $systemRequest->status->label(),
            'message' => $systemRequest->status->userNotificationMessage($context)
        ];
    }

    private function getContextForRevision($systemRequest): ?array
    {
        if ($systemRequest->need_review) {
            return ['verifikator_role' => null];
        }
        return ['verifikator_role' => $systemRequest->current_division];
    }
}
