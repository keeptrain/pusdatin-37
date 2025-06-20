<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $verifikator;

    /**
     * Create a new notification instance.
     */
    public function __construct($request, $verifikator = null)
    {
        $this->request = $request;
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
        // Bisa id InformationSystemRequest atau PublicRelationRequest
        $id = $this->request->id;
        $requestObject = get_class($this->request);
        $statusObject = null;
        $messageContext = [];

        $userName = User::findOrFail($this->request->user_id)->name;

        // Logika spesifik berdasarkan tipe instance request
        if ($this->request instanceof InformationSystemRequest) {
            $statusObject = $this->request->status;
            $messageContext = [
                'responsible_person' => $userName
            ];
        } elseif ($this->request instanceof PublicRelationRequest) {
            $statusObject = $this->request->status;
            $messageContext = [
                'responsible_person' => $userName
            ];
        } else {
            return [
                'id' => null,
                'status' => 'unknown_type',
                'message' => 'Tipe request tidak dikenal.'
            ];
        }

        if (is_null($statusObject)) {
            return [
                'id' => $id,
                'status' => 'invalid_status_object',
                'message' => 'Objek status tidak valid untuk request ini.'
            ];
        }

        return [
            'requestable_type' => $requestObject,
            'requestable_id' => $id,
            'username' => $userName,
            'status' => $statusObject->label(),
            'message' => $statusObject->userNotificationMessage($messageContext)
        ];
    }
}
