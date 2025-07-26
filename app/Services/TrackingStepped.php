<?php

namespace App\Services;

use App\Enums\Division;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Illuminate\Database\Eloquent\Model;
use App\States\InformationSystem\{
    ApprovedKapusdatin,
    ApprovedKasatpel,
    Completed,
    Disposition,
    Pending,
    Process,
    Rejected,
    Replied,
    RepliedKapusdatin
};
use App\States\PublicRelation\{
    Pending as PRPending,
    PromkesQueue,
    PromkesComplete,
    PusdatinQueue,
    PusdatinProcess,
    Completed as PRCompleted
};

class TrackingStepped
{
    private static array $stateInstances = [];

    public static function SiDataRequest(InformationSystemRequest $systemRequest): array
    {
        $statusTrack = $systemRequest->trackingHistorie;
        $statuses = self::mapStatuses($systemRequest, [
            Pending::class,
            Disposition::class,
            ApprovedKasatpel::class,
            ApprovedKapusdatin::class,
            Process::class,
            Completed::class,
        ], $statusTrack, $systemRequest->current_division);

        if ($systemRequest->status instanceof Rejected) {
            $statuses = self::handleRejectedStatus($systemRequest, $statuses, $statusTrack);
        }

        if ($systemRequest->status instanceof Replied) {
            $statuses = self::handleRepliedStatus($systemRequest, $statuses, $statusTrack);
        }

        if ($systemRequest->status instanceof RepliedKapusdatin) {
            $statuses = self::handleRepliedKapusdatinStatus($systemRequest, $statuses, $statusTrack);
        }

        return array_values($statuses);
    }

    public static function PublicRelationRequest(PublicRelationRequest $prRequest): array
    {
        return self::mapStatuses($prRequest, [
            PRPending::class,
            PromkesQueue::class,
            PromkesComplete::class,
            PusdatinQueue::class,
            PusdatinProcess::class,
            PRCompleted::class,
        ], $prRequest->trackingHistorie);
    }

    public static function currentIndex(Model $model, array $statuses): int
    {
        if ($model instanceof InformationSystemRequest) {
            $currentStatusLabel = $model->status instanceof Rejected
                ? self::getStateInstance(Rejected::class, $model)->label()
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
        $rejectedState = self::getStateInstance(Rejected::class, $systemRequest);
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
            $repliedState = self::getStateInstance(Replied::class, $systemRequest);
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
            $repliedKapusdatinState = self::getStateInstance(RepliedKapusdatin::class, $systemRequest);
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