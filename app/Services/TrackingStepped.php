<?php

namespace App\Services;

use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Illuminate\Database\Eloquent\Model;

class TrackingStepped
{
    public static function SiDataRequest(Letter $letter)
    {
        $orderedStates = [
            \App\States\Pending::class,
            \App\States\Disposition::class,
            \App\States\Process::class,
            \App\States\ApprovedKasatpel::class,
            \App\States\ApprovedKapusdatin::class,
        ];

        $statuses = collect($orderedStates)
            ->map(function ($stateClass) use ($letter) {
                $state = new $stateClass($letter);
                return [
                    'label' => $state->label(),
                    'icon' => $state->icon(),
                ];
            })
            ->values()
            ->toArray();

        // Logika untuk "Rejected"
        if ($letter->status instanceof \App\States\Rejected) {
            $statuses = array_filter($statuses, function ($status) use ($letter) {
                return $status['label'] !== (new \App\States\ApprovedKasatpel($letter))->label();
            });
        }

        // Logika untuk "Replied"
        if ($letter->status instanceof \App\States\Replied) {
            $activeChecking = $letter->active_checking;

            if ($activeChecking === 2) {
                array_splice($statuses, 4, 0, [
                    ['label' => (new \App\States\Replied($letter))->label(), 'icon' => (new \App\States\Replied($letter))->icon()]
                ]);
            } elseif (in_array($activeChecking, [3, 4])) {
                array_splice($statuses, 3, 0, [
                    ['label' => (new \App\States\Replied($letter))->label(), 'icon' => (new \App\States\Replied($letter))->icon()]
                ]);
            }
        }

        // Logika untuk menambahkan "Rejected"
        if ($letter->status instanceof \App\States\Rejected) {
            $statuses[4] = [
                'label' => (new \App\States\Rejected($letter))->label(),
                'icon' => (new \App\States\Rejected($letter))->icon(),
            ];
        }

        return array_values($statuses);
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
