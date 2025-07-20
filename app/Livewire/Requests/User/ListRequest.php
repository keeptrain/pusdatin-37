<?php

namespace App\Livewire\Requests\User;

use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InformationSystemRequest;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\PublicRelationStatus;
use App\States\InformationSystem\InformationSystemStatus;

#[Title('Daftar Permohonan')]
class ListRequest extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public string $search = '';

    #[Computed]
    public function allRequests()
    {
        // Merge 2 query from InformationSystemRequest and PublicRelationRequest
        $combinedQuery = $this->getCombinedQuery();

        $paginator = $this->paginateAndTransform($combinedQuery);

        return $paginator;
    }

    /**
     * Combine query from InformationSystemRequest and PublicRelationRequest.
     */
    protected function getCombinedQuery()
    {
        // Query for InformationSystemRequest
        $informationSystemRequestsQuery = $this->buildBaseQuery(InformationSystemRequest::class, 'Sistem Informasi & Data', 'title');

        // Query for PublicRelationRequest
        $publicRelationRequestsQuery = $this->buildBaseQuery(PublicRelationRequest::class, 'Kehumasan', 'theme', true);

        // Union all query
        return $informationSystemRequestsQuery->unionAll($publicRelationRequestsQuery);
    }

    /**
     * Build base query.
     */
    protected function buildBaseQuery($modelClass, string $type, string $informationField, bool $isNullRevision = false)
    {
        $query = $modelClass::select(
            'id',
            DB::raw("'$type' as type"),
            "$informationField as information",
            'status',
            'created_at',
            $isNullRevision ? DB::raw('null as active_revision') : 'active_revision'
        )
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at');

        // Search condition if search term exists
        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $query->where(function ($q) use ($informationField, $searchTerm, $type) {
                $q->where(DB::raw("LOWER($informationField)"), 'like', "%$searchTerm%")
                    // ->orWhere(DB::raw("LOWER(status)"), 'like', "%$searchTerm%")
                    ->orWhere(DB::raw("LOWER(DATE_FORMAT(created_at, '%d %M %Y, %H:%i'))"), 'like', "%$searchTerm%")
                    ->orWhere(DB::raw("LOWER('$type')"), 'like', "%$searchTerm%");
            });
        }

        return $query->getQuery();
    }

    /**
     * Paginate and transform data.
     */
    protected function paginateAndTransform($combinedQuery)
    {
        // Get SQL and bindings from combined query
        $sql = $combinedQuery->toSql();

        // Build main query for pagination
        $mainQuery = DB::table(DB::raw("($sql) as combined_table"))
            ->mergeBindings($combinedQuery)
            ->orderBy('created_at', 'desc');

        // Paginate using Livewire
        $paginator = $mainQuery->paginate($this->perPage);

        // Transform collection
        $transformedCollection = $paginator->getCollection()->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'type' => $item->type,
                'information' => $item->information,
                'status' => $this->getStatusLabel($item->type, $item->status),
                'created_at' => Carbon::parse($item->created_at)->format('d F Y, H:i'),
                'active_revision' => $item->active_revision
            ];
        });

        // Set transformed collection
        $paginator->setCollection($transformedCollection);

        return $paginator;
    }

    /**
     * Get status label.
     */
    protected function getStatusLabel(string $type, string $status)
    {
        try {
            if ($type === 'Sistem Informasi & Data') {
                $state = InformationSystemStatus::make($status, new InformationSystemRequest());
                return $state;
            }

            if ($type === 'Kehumasan') {
                $state = PublicRelationStatus::make($status, new PublicRelationRequest());
                return $state;
            }
        } catch (\Exception $e) {
            report($e); // Log error
        }

        return $status; // Fallback if no type matches
    }
}
