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

    public int $page = 1;
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
     * Menggabungkan query dari Letter dan PublicRelationRequest.
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
    protected function buildBaseQuery($modelClass, $type, $informationField, $isNullRevision = false)
    {
        return $modelClass::select(
            'id',
            DB::raw("'$type' as type"),
            "$informationField as information",
            'status',
            'created_at',
            $isNullRevision ? DB::raw('null as active_revision') : 'active_revision'
        )->where('user_id', auth()->id())->whereNull('deleted_at')->getQuery();
    }

    /**
     * Paginate and transform data.
     */
    protected function paginateAndTransform($combinedQuery)
    {
        // SQL dan binding dari query gabungan
        $sql = $combinedQuery->toSql();

        // Query untuk paginasi
        $mainQuery = DB::table(DB::raw("($sql) as combined_table"))
            ->mergeBindings($combinedQuery)
            ->orderBy('created_at', 'desc');

        // Paginasi menggunakan metode Livewire
        $paginator = $mainQuery->paginate($this->perPage);

        // Transformasi setiap item dalam koleksi
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

        // Set koleksi yang sudah ditransformasi
        $paginator->setCollection($transformedCollection);

        return $paginator;
    }

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

        return $status; // Fallback jika tidak ada tipe yang cocok
    }

    // Reset pagination saat search berubah
    public function updatingSearchQuery()
    {
        $this->resetPage();
    }
}
