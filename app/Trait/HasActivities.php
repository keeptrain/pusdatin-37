<?php

namespace App\Trait;


use Carbon\Carbon;
use App\Models\User;
use App\States\Replied;
use App\States\Rejected;
use App\States\Disposition;
use App\States\ApprovedKasatpel;
use App\States\ApprovedKapusdatin;
use Illuminate\Support\Collection;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\Completed;
use App\Models\Letters\RequestStatusTrack;
use Illuminate\Support\Facades\Notification;
use App\States\PublicRelation\PromkesComplete;
use App\Notifications\NewServiceRequestNotification;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Notifications\LetterServiceRequestNotification;
use App\Notifications\PublicRelationRequestNotification;
use App\States\PublicRelation\PusdatinQueue;
use App\States\RepliedKapusdatin;

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

    public function getGroupedRequestStatusTracks(): Collection
    {
        $allTracks = $this->requestStatusTrack()->get(['statusable_type', 'statusable_id', 'action', 'notes', 'created_at']); // Memanggil relasi yang didefinisikan di trait ini

        return $allTracks
            ->sortByDesc('created_at')
            ->groupBy([
                fn($item) => Carbon::parse($item->created_at)->format('Y-m-d'),
                fn($item) => Carbon::parse($item->created_at)->format('H:i:s')
            ]);
    }

    public function logStatus(?string $notes)
    {
        $divisionParamForTrackingMessage = ($this->status instanceof ApprovedKasatpel || $this->status instanceof Process)
            ? (int) $this->current_division
            : (int) $this->active_checking;

        return $this->requestStatusTrack()->create([
            'action' => $this->status->trackingMessage($divisionParamForTrackingMessage),
            'notes' => $notes ?? null,
        ]);
    }

    public function logStatusRevision(?string $notes, array $partName)
    {
        return $this->requestStatusTrack()->create([
            'action' => auth()->user()->name . " telah melakukan revisi di bagian " . implode(' ,', $partName),
            'notes' => $notes ?? null,
        ]);
    }

    public function logStatusReview(?string $action, ?string $notes)
    {
        return $this->requestStatusTrack()->create([
            'action' => $action,
            'notes' => $notes
        ]);
    }

    public function logStatusCustom(?string $action)
    {
        return $this->requestStatusTrack()->create([
            'action' => $action,
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

    public function sendDispositionServiceRequestNotification()
    {
        $siRequest = $this;
        $newStatus = $siRequest->status;

        if ($newStatus == Disposition::class) {
            $finalRecipients = User::role($siRequest->current_division)->get();

            // Kirim notifikasi ke setiap roles
            if ($finalRecipients->isNotEmpty()) {
                $finalRecipients->each(function ($user) use ($siRequest) {
                    $user->notify(new LetterServiceRequestNotification($siRequest));
                });
            }
        } elseif ($newStatus == Rejected::class) {
            $finalRecipient = User::findOrFail($siRequest->user_id);

            // Kirim notifikasi ke pemohon
            $finalRecipient->notify(new LetterServiceRequestNotification($siRequest));
        }
    }

    public function sendProcessServiceRequestNotification(): void
    {
        $currentStatusClass = get_class($this->status);

        $notificationLogicMap = [
            ApprovedKapusdatin::class => function (): void {
                $recipient = User::role($this->current_division)->get();
                Notification::send($recipient, new LetterServiceRequestNotification($this));
            },
            RepliedKapusdatin::class => function () {
                if ($this->need_review) {
                    $recipients = User::role($this->active_checking)->get();
                    if ($recipients->isNotEmpty()) {
                        Notification::send($recipients, new LetterServiceRequestNotification($this));
                    }
                } else {
                    $recipient = User::findOrFail($this->user_id);
                    $recipient->notify(new LetterServiceRequestNotification($this));
                }
            },
            ApprovedKasatpel::class => function () {
                $recipients = User::role($this->active_checking)->get();
                if ($recipients->isNotEmpty()) {
                    Notification::send($recipients, new LetterServiceRequestNotification($this));
                }
            },
            Replied::class => function () {
                if ($this->need_review) {
                    $recipients = User::role($this->active_checking)->get();
                    if ($recipients->isNotEmpty()) {
                        Notification::send($recipients, new LetterServiceRequestNotification($this));
                    }
                } else {
                    $recipient = User::findOrFail($this->user_id);
                    $recipient->notify(new LetterServiceRequestNotification($this));
                }
            },
            Rejected::class => function () {
                $recipient = User::findOrFail($this->user_id);
                $recipient->notify(new LetterServiceRequestNotification($this));
            }
        ];

        if (isset($notificationLogicMap[$currentStatusClass])) {
            $notificationLogicMap[$currentStatusClass]();
        }
    }

    public function sendPrRequestNotification()
    {
        if (!$this instanceof PublicRelationRequest) {
            return;
        }

        $currentStatusClass = get_class($this->status);

        $notificationLogicMap = [
            PromkesComplete::class => function () {
                $recipients = User::role('head_verifier')->get();
                if ($recipients->isNotEmpty()) {
                    Notification::send($recipients, new PublicRelationRequestNotification($this));
                }
            },
            PusdatinQueue::class => function () {
                $recipients = User::role('pr_verifier')->get();
                if ($recipients->isNotEmpty()) {
                    Notification::send($recipients, new PublicRelationRequestNotification($this));
                }
            },
            Completed::class => function () {
                $recipient = User::findOrFail($this->user_id);
                $recipient->notify(new PublicRelationRequestNotification($this));
            }
        ];

        if (isset($notificationLogicMap[$currentStatusClass])) {
            $notificationLogicMap[$currentStatusClass]();
        }
    }
}
