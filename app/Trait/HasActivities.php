<?php

namespace App\Trait;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\Letters\RequestStatusTrack;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewServiceRequestNotification;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasActivities
{
    public function requestStatusTrack()
    {
        return $this->morphMany(RequestStatusTrack::class, 'statusable')->latest('created_at');
    }

    public function getRawRequestStatusTracksRelation(): MorphMany
    {
        return $this->requestStatusTrack(); // Memanggil relasi yang didefinisikan di trait ini
    }

    public function logStatus()
    {
        return $this->requestStatusTrack()->create([
            'action' => $this->status->trackingMessage(null),
            'created_by' => auth()->user()->name
        ]);
    }

    public function sendNewServiceRequestNotification($recipients, ?User $verifikator = null)
    {
        $requestData = $this;
        $finalRecipients = collect();

        if (is_string($recipients)) {
            $finalRecipients = User::role($recipients)->get();
        } elseif ($recipients instanceof User) {
            $finalRecipients = collect([$recipients]);
        } elseif ($recipients instanceof Collection && $recipients->first() instanceof User) {
            $finalRecipients = $recipients;
        } else {
            report(new \InvalidArgumentException('Unsupported recipients type for notification.'));
            return;
        }

        if ($finalRecipients->isNotEmpty()) {
            Notification::sendNow($finalRecipients, new NewServiceRequestNotification($requestData, $verifikator));
        }
    }

    public function getGroupedRequestStatusTracks(): Collection
    {
        $allTracks = $this->requestStatusTrack()->get(['statusable_type', 'statusable_id', 'action', 'created_at']); // Memanggil relasi yang didefinisikan di trait ini

        return $allTracks
            ->sortByDesc('created_at')
            ->groupBy([
                fn($item) => Carbon::parse($item->created_at)->format('Y-m-d'),
                fn($item) => Carbon::parse($item->created_at)->format('H:i:s')
            ]);
    }
}
