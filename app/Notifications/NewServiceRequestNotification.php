<?php

namespace App\Notifications;

use App\States\Pending;
use App\States\Process;
use App\States\Approved;
use App\States\Rejected;
use App\States\Replied;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $letter;
    public $verifikator;

    /**
     * Create a new notification instance.
     */
    public function __construct($letter, $verifikator = null)
    {
        $this->letter = $letter;
        $this->verifikator = $verifikator;
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
        $status = $this->letter->status;

        $context = match(true) {
            $status instanceof Pending => [
                'responsible_person' => $this->letter->responsible_person,
            ],
            $status instanceof Process => [
                'verifikator' => $this->verifikator
            ],
            $status instanceof Replied => [
                'verifikator_role' => $this->verifikator->getRoleNames()->first(),
            ],
            $status instanceof Approved,
            $status instanceof Rejected => [],
            default => []
        };

        return [
            'id' => $this->letter->id,
            'letter_category' => 'Applications',
            'status' => $status,
            'message' => $status->userNotificationMessage($context)
        ];
    }
}
