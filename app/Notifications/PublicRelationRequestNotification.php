<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\PublicRelationRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PusdatinProcess;

class PublicRelationRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public PublicRelationRequest $publicRelationRequest;
    /**
     * Create a new notification instance.
     */
    public function __construct($publicRelationRequest)
    {
        $this->publicRelationRequest = $publicRelationRequest;
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
        $prRequest = $this->publicRelationRequest;
        $context = [];

        $context = match (get_class($prRequest->status)) {
            PromkesComplete::class => $this->getContextResponsiblePerson($prRequest),
            PusdatinProcess::class =>  $this->getContextResponsiblePerson($prRequest),
            default => [],
        };

        return [
            'requestable_type' => get_class($prRequest),
            'requestable_id' => $prRequest->id,
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
