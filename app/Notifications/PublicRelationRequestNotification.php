<?php

namespace App\Notifications;

use App\Models\User;
use App\States\PublicRelation\Completed;
use Illuminate\Bus\Queueable;
use App\Models\PublicRelationRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PusdatinProcess;
use Illuminate\Support\Facades\Log;
use App\Mail\Requests\PublicRelation\CompletedMail;

class PublicRelationRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public PublicRelationRequest $publicRelationRequest, public ?array $data = null)
    {
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

    public function toMail(object $notifiable)
    {
        if (!$this->publicRelationRequest->status instanceof Completed) {
            return;
        }

        try {
            return new CompletedMail($this->data)->to($notifiable->email);
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
        $prRequest = $this->publicRelationRequest;

        $userName = User::findOrFail($prRequest->user_id)->name;

        $context = $this->getContextResponsiblePerson($prRequest);

        return [
            'requestable_type' => get_class($prRequest),
            'requestable_id' => $prRequest->id,
            'username' => $userName,
            'status' => $prRequest->status->label(),
            'message' => $prRequest->status->userNotificationMessage($context)
        ];
    }

    private function getContextResponsiblePerson($prRequest): array
    {
        $userName = User::findOrFail($prRequest->user_id)->name;
        return ['responsible_person' => $userName];
    }
}
