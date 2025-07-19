<?php

namespace App\Services;

use App\Enums\Division;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Illuminate\Database\Eloquent\Model;

class TrackingStepped
{
    private static array $stateInstances = [];

    public static function SiDataRequest(InformationSystemRequest $systemRequest): array
    {
        $statusTrack = $systemRequest->trackingHistorie;
        $statuses = self::mapStatuses($systemRequest, [
            \App\States\InformationSystem\Pending::class,
            \App\States\InformationSystem\Disposition::class,
            \App\States\InformationSystem\ApprovedKasatpel::class,
            \App\States\InformationSystem\ApprovedKapusdatin::class,
            \App\States\InformationSystem\Process::class,
            \App\States\InformationSystem\Completed::class,
        ], $statusTrack, $systemRequest->current_division);

        if ($systemRequest->status instanceof \App\States\InformationSystem\Rejected) {
            $statuses = self::handleRejectedStatus($systemRequest, $statuses, $statusTrack);
        }

        if ($systemRequest->status instanceof \App\States\InformationSystem\Replied) {
            $statuses = self::handleRepliedStatus($systemRequest, $statuses, $statusTrack);
        }

        if ($systemRequest->status instanceof \App\States\InformationSystem\RepliedKapusdatin) {
            $statuses = self::handleRepliedKapusdatinStatus($systemRequest, $statuses, $statusTrack);
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
        ], $prRequest->trackingHistorie);
    }

    public static function currentIndex(Model $model, array $statuses): int
    {
        if ($model instanceof InformationSystemRequest) {
            $currentStatusLabel = $model->status instanceof \App\States\InformationSystem\Rejected
                ? self::getStateInstance(\App\States\InformationSystem\Rejected::class, $model)->label()
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

                $trackEntry = $statusTrack->firstWhere('message', $trackMessage);

                return [
                    'label' => $state->label(),
                    'icon' => $state->icon(),
                    'created_at' => $trackEntry?->created_at,
                ];
            })
            ->values()
            ->all();
    }

    private static function handleRejectedStatus(InformationSystemRequest $systemRequest, array $statuses, $statusTrack): array
    {
        $statuses = array_slice($statuses, 0, 2);
        $rejectedState = self::getStateInstance(\App\States\InformationSystem\Rejected::class, $systemRequest);
        $rejectedTrack = $statusTrack->firstWhere(
            'message',
            $rejectedState->trackingMessage(Division::HEAD_ID->value)
        );

        $statuses[2] = [
            'label' => $rejectedState->label(),
            'icon' => $rejectedState->icon(),
            'created_at' => $rejectedTrack?->created_at,
        ];

        return $statuses;
    }

    private static function handleRepliedStatus(InformationSystemRequest $systemRequest, array $statuses, $statusTrack): array
    {
        if (in_array($systemRequest->active_checking, [Division::SI_ID->value, Division::DATA_ID->value], true)) {
            $repliedState = self::getStateInstance(\App\States\InformationSystem\Replied::class, $systemRequest);
            $repliedTrack = $statusTrack->firstWhere(
                'message',
                $repliedState->trackingMessage($systemRequest->current_division)
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

    private static function handleRepliedKapusdatinStatus(InformationSystemRequest $systemRequest, array $statuses, $statusTrack): array
    {
        if ($systemRequest->active_checking === Division::HEAD_ID->value) {
            $repliedKapusdatinState = self::getStateInstance(\App\States\InformationSystem\RepliedKapusdatin::class, $systemRequest);
            $repliedKapusdatinTrack = $statusTrack->firstWhere(
                'message',
                $repliedKapusdatinState->trackingMessage($systemRequest->current_division)
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