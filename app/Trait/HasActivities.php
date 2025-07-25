<?php

namespace App\Trait;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\Completed;
use App\Models\TrackingHistorie;
use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\Notification;
use App\States\PublicRelation\PromkesComplete;
use App\Notifications\NewServiceRequestNotification;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Notifications\SiServiceRequestNotification;
use App\Notifications\PublicRelationRequestNotification;
use App\Notifications\RevisionRequestNotification;
use App\States\PublicRelation\PusdatinQueue;
use App\States\InformationSystem\RepliedKapusdatin;
use App\States\InformationSystem\Replied;
use App\States\InformationSystem\Rejected;
use App\States\InformationSystem\Disposition;
use App\States\InformationSystem\ApprovedKasatpel;
use App\States\InformationSystem\ApprovedKapusdatin;

trait HasActivities
{
    public function trackingHistorie()
    {
        return $this->morphMany(TrackingHistorie::class, 'requestable')->latest('created_at');
    }

    public function getRawTrackingHistorieRelation(): MorphMany
    {
        return $this->trackingHistorie();
    }

    public function getGroupedTrackingHistorie(): Collection
    {
        // Get relation request status track
        $allTracks = $this->trackingHistorie;

        return $allTracks
            ->sortByDesc('created_at')
            ->groupBy([
                fn($item) => Carbon::parse($item->created_at)->format('Y-m-d'),
                fn($item) => Carbon::parse($item->created_at)->format('H:i:s')
            ]);
    }

    public function logStatus(?string $notes)
    {
        $divisionParamForTrackingMessage = ($this->status instanceof ApprovedKasatpel || $this->status instanceof Process && $this->status instanceof \App\States\InformationSystem\Completed)
            ? (int) $this->current_division
            : (int) $this->active_checking;

        return $this->trackingHistorie()->create([
            'message' => $this->status->trackingMessage($divisionParamForTrackingMessage),
            'notes' => $notes ?? null,
        ]);
    }

    public function logStatusRevision(?string $notes, array $partName)
    {
        return $this->trackingHistorie()->create([
            'message' => auth()->user()->name . " telah melakukan revisi di bagian " . implode(' ,', $partName),
            'notes' => $notes ?? null,
        ]);
    }

    public function logStatusReview(?string $message, ?string $notes)
    {
        return $this->trackingHistorie()->create([
            'message' => $message,
            'notes' => $notes
        ]);
    }

    public function logStatusCustom(?string $message)
    {
        return $this->trackingHistorie()->create([
            'message' => $message,
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

        if ($newStatus instanceof Disposition) {
            $finalRecipients = User::role($siRequest->current_division)->get();

            // Send notification to division
            if ($finalRecipients->isNotEmpty()) {
                $finalRecipients->each(function ($user) use ($siRequest) {
                    $user->notify(new SiServiceRequestNotification($siRequest));
                });
            }
        } elseif ($newStatus instanceof Rejected) {
            $finalRecipient = User::findOrFail($siRequest->user_id);

            // Send notification to requestor
            $finalRecipient->notify(new SiServiceRequestNotification($siRequest));
        }
    }

    public function sendProcessServiceRequestNotification(?array $data = null)
    {
        if (!$this instanceof InformationSystemRequest) {
            return;
        }

        $currentStatusClass = $this->status::class;

        $callback = match ($currentStatusClass) {
            Replied::class, RepliedKapusdatin::class => function () use ($data) {
                    $this->revisionContext($data);
                },
            ApprovedKasatpel::class => function (): void {
                    $recipients = User::role($this->active_checking)->get();
                    if ($recipients->isNotEmpty()) {
                        Notification::send($recipients, new SiServiceRequestNotification($this));
                    }
                },
            ApprovedKapusdatin::class => function (): void {
                    $recipient = User::role($this->current_division)->get();
                    Notification::send($recipient, new SiServiceRequestNotification($this));
                },
            RepliedKapusdatin::class => function (): void {
                    if ($this->need_review) {
                        $recipients = User::role($this->active_checking)->get();
                        if ($recipients->isNotEmpty()) {
                            Notification::send($recipients, new SiServiceRequestNotification($this));
                        }
                    } else {
                        $recipient = User::findOrFail($this->user_id);
                        $recipient->notify(new SiServiceRequestNotification($this));
                    }
                },
            Rejected::class => function (): void {
                    $recipient = User::findOrFail($this->user_id);
                    $recipient->notify(new SiServiceRequestNotification($this));
                },
            default => static fn() => null,
        };

        if (is_callable($callback)) {
            $callback();
        }
    }

    protected function revisionContext($data)
    {
        if ($this->need_review) {
            $recipients = User::role($this->active_checking)->get();
            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, new RevisionRequestNotification($this));
            }
        } else {
            $recipient = User::findOrFail($this->user_id);
            $recipient->notify(new RevisionRequestNotification($this, $data));
        }
    }

    public function sendPrRequestNotification(?array $data = null)
    {
        if (!$this instanceof PublicRelationRequest) {
            return;
        }

        $currentStatusClass = $this->status::class;

        $callable = match ($currentStatusClass) {
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
            Completed::class => function () use ($data) {
                    $recipient = User::findOrFail($this->user_id);
                    $recipient->notify(new PublicRelationRequestNotification($this, $data));
                },
            default => static fn() => null,
        };

        if (is_callable($callable)) {
            $callable();
        }
    }
}
