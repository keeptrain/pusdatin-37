<?php

namespace App\Services;

use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Division;

class TrackingStepped
{
    public static function SiDataRequest(Letter $systemRequest)
    {
        $orderedStates = [
            \App\States\Pending::class,
            \App\States\Disposition::class,
            \App\States\ApprovedKasatpel::class,
            \App\States\ApprovedKapusdatin::class,
            \App\States\Process::class,
            \App\States\Completed::class,
        ];

        $statuses = collect($orderedStates)
            ->map(function ($stateClass) use ($systemRequest) {
                $state = new $stateClass($systemRequest);
                return [
                    'label' => $state->label(),
                    'icon' => $state->icon(),
                ];
            })
            ->values()
            ->toArray();

        // Menambahkan "Rejected"
        if ($systemRequest->status instanceof \App\States\Rejected) {
            $statuses = array_slice($statuses, 0, 2);

            // Tambahkan status "Rejected" pada indeks [2]
            $statuses[2] = [
                'label' => (new \App\States\Rejected($systemRequest))->label(),
                'icon' => (new \App\States\Rejected($systemRequest))->icon(),
            ];

        }

        $activeChecking = $systemRequest->active_checking;

        if ($systemRequest->status instanceof \App\States\Replied) {
            if ($activeChecking == Division::SI_ID->value || $activeChecking == Division::DATA_ID->value) {
                array_splice($statuses, 2, 0, [
                    ['label' => (new \App\States\Replied($systemRequest))->label(), 'icon' => (new \App\States\Replied($systemRequest))->icon()]
                ]);
            }
        }

        if ($systemRequest->status instanceof \App\States\RepliedKapusdatin && $activeChecking == Division::HEAD_ID->value) {
            array_splice($statuses, 3, 0, [
                ['label' => (new \App\States\RepliedKapusdatin($systemRequest))->label(), 'icon' => (new \App\States\RepliedKapusdatin($systemRequest))->icon()]
            ]);
        }

        return array_values($statuses); // Reindex array
    }

    public static function PublicRelationRequest(PublicRelationRequest $prRequest)
    {
        $orderedStates = [
            \App\States\PublicRelation\Pending::class,
            \App\States\PublicRelation\PromkesQueue::class,
            \App\States\PublicRelation\PromkesComplete::class,
            \App\States\PublicRelation\PusdatinQueue::class,
            \App\States\PublicRelation\PusdatinProcess::class,
            \App\States\PublicRelation\Completed::class,
        ];

        $statuses = collect($orderedStates)
            ->map(function ($stateClass) use ($prRequest) {
                $state = new $stateClass($prRequest);
                return [
                    'label' => $state->label(),
                    'icon' => $state->icon(),
                ];
            })
            ->values()
            ->toArray();

        return array_values($statuses); // Reindex array
    }

    public static function currentIndex(Model $model, array $statuses)
    {
        $statusLabels = array_column($statuses, 'label');

        $statusMap = array_flip($statusLabels);

        if ($model instanceof Letter) {
            $currentStatusLabel = $model->status instanceof \App\States\Rejected
                ? (new \App\States\Rejected($model))->label()
                : $model->status->label();
        }

        $currentStatusLabel = $model->status->label();

        return $statusMap[$currentStatusLabel] ?? 0;
    }
}
