<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $letter;
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
        return [
            'id' => $this->letter->id,
            'letter_category' => 'Applications',
            'status' => $this->letter->status,
            'message' => "Surat baru telah diajukan oleh" .  $this->letter->responsible_person
        ];
    }
}
