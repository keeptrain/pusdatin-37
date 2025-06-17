<?php

namespace App\Services;

use App\Enums\Division;
use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Illuminate\Database\Eloquent\Model;

class TrackingStepped
{
    private static array $stateInstances = [];

    public static function SiDataRequest(Letter $siRequest): array
    {
        $statusTrack = $siRequest->requestStatusTrack;
        $statuses = self::mapStatuses($siRequest, [
            \App\States\Pending::class,
            \App\States\Disposition::class,
            \App\States\ApprovedKasatpel::class,
            \App\States\ApprovedKapusdatin::class,
            \App\States\Process::class,
            \App\States\Completed::class,
        ], $statusTrack, $siRequest->current_division);

        if ($siRequest->status instanceof \App\States\Rejected) {
            $statuses = self::handleRejectedStatus($siRequest, $statuses, $statusTrack);
        }

        if ($siRequest->status instanceof \App\States\Replied) {
            $statuses = self::handleRepliedStatus($siRequest, $statuses, $statusTrack);
        }

        if ($siRequest->status instanceof \App\States\RepliedKapusdatin) {
            $statuses = self::handleRepliedKapusdatinStatus($siRequest, $statuses, $statusTrack);
        }

        return array_values($statuses);
    }

    public static function PublicRelationRequest(PublicRelationRequest $prRequest): array
    {
        return self::mapStatuses($prRequest, [
            \App\States\PublicRelation\Pending::class,
            \App\States\PublicRelation\PromkesQueue::class,
            \App\States\PublicRelation\PromkesComplete::class,
            \App\States\PublicRelation\PusdatinQueue::class,
            \App\States\PublicRelation\PusdatinProcess::class,
            \App\States\PublicRelation\Completed::class,
        ], $prRequest->requestStatusTrack);
    }

    public static function currentIndex(Model $model, array $statuses): int
    {
        if ($model instanceof Letter) {
            $currentStatusLabel = $model->status instanceof \App\States\Rejected
                ? self::getStateInstance(\App\States\Rejected::class, $model)->label()
                : $model->status->label();
        } else {
            $currentStatusLabel = $model->status->label();
        }

        return array_flip(array_column($statuses, 'label'))[$currentStatusLabel] ?? 0;
    }

    private static function mapStatuses(
        Model $model,
        array $stateClasses,
        $statusTrack,
        ?int $division = null
    ): array {
        return collect($stateClasses)
            ->map(function (string $stateClass) use ($model, $statusTrack, $division) {
                $state = self::getStateInstance($stateClass, $model);
                $trackMessage = $state->trackingMessage($division);

                $trackEntry = $statusTrack->firstWhere('action', $trackMessage);

                return [
                    'label' => $state->label(),
                    'icon' => $state->icon(),
                    'created_at' => $trackEntry?->created_at,
                ];
            })
            ->values()
            ->all();
    }

    private static function handleRejectedStatus(Letter $siRequest, array $statuses, $statusTrack): array
    {
        $statuses = array_slice($statuses, 0, 2);
        $rejectedState = self::getStateInstance(\App\States\Rejected::class, $siRequest);
        $rejectedTrack = $statusTrack->firstWhere(
            'action',
            $rejectedState->trackingMessage(Division::HEAD_ID->value)
        );

        $statuses[2] = [
            'label' => $rejectedState->label(),
            'icon' => $rejectedState->icon(),
            'created_at' => $rejectedTrack?->created_at,
        ];

        return $statuses;
    }

    private static function handleRepliedStatus(Letter $siRequest, array $statuses, $statusTrack): array
    {
        if (in_array($siRequest->active_checking, [Division::SI_ID->value, Division::DATA_ID->value], true)) {
            $repliedState = self::getStateInstance(\App\States\Replied::class, $siRequest);
            $repliedTrack = $statusTrack->firstWhere(
                'action',
                $repliedState->trackingMessage($siRequest->current_division)
            );

            array_splice($statuses, 2, 0, [
                [
                    'label' => $repliedState->label(),
                    'icon' => $repliedState->icon(),
                    'created_at' => $repliedTrack?->created_at,
                ]
            ]);
        }

        return $statuses;
    }

    private static function handleRepliedKapusdatinStatus(Letter $siRequest, array $statuses, $statusTrack): array
    {
        if ($siRequest->active_checking === Division::HEAD_ID->value) {
            $repliedKapusdatinState = self::getStateInstance(\App\States\RepliedKapusdatin::class, $siRequest);
            $repliedKapusdatinTrack = $statusTrack->firstWhere(
                'action',
                $repliedKapusdatinState->trackingMessage($siRequest->current_division)
            );

            array_splice($statuses, 3, 0, [
                [
                    'label' => $repliedKapusdatinState->label(),
                    'icon' => $repliedKapusdatinState->icon(),
                    'created_at' => $repliedKapusdatinTrack?->created_at,
                ]
            ]);
        }

        return $statuses;
    }

    private static function getStateInstance(string $stateClass, Model $model): object
    {
        $key = $stateClass . '_' . $model->getKey();
        if (!isset(self::$stateInstances[$key])) {
            self::$stateInstances[$key] = new $stateClass($model);
        }
        return self::$stateInstances[$key];
    }
}