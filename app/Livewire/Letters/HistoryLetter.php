<?php

namespace App\Livewire\Letters;

use Carbon\Carbon;
use Livewire\Component;
use App\States\LetterStatus;
use Livewire\WithPagination;
use App\Models\Letters\Letter;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use App\States\PublicRelation\PublicRelationStatus;

class HistoryLetter extends Component
{
    use WithPagination;

    public $page = 1;
    public $perPage = 10;
    public $searchQuery = '';

    #[Computed]
    public function allRequests()
    {
        // Gabungkan query dari kedua model
        $combinedQuery = $this->getCombinedQuery();

        // Eksekusi query gabungan dengan paginasi
        $paginator = $this->paginateAndTransform($combinedQuery);

        return $paginator;
    }

    /**
     * Menggabungkan query dari Letter dan PublicRelationRequest.
     */
    protected function getCombinedQuery()
    {
        // Query untuk Letter
        $informationSystemRequestsQuery = $this->buildBaseQuery(Letter::class, 'Sistem Informasi & Data', 'title');

        // Query untuk PublicRelationRequest
        $publicRelationRequestsQuery = $this->buildBaseQuery(PublicRelationRequest::class, 'Kehumasan', 'theme', true);

        // Gabungkan kedua query dengan UNION ALL
        return $informationSystemRequestsQuery->unionAll($publicRelationRequestsQuery);
    }

    /**
     * Membuat query dasar untuk model tertentu.
     */
    protected function buildBaseQuery($modelClass, $type, $informationField, $isNullRevision = false)
    {
        $query = $modelClass::select(
            'id',
            DB::raw("'$type' as type"),
            "$informationField as information",
            'status',
            'created_at',
            $isNullRevision ? DB::raw('null as active_revision') : 'active_revision'
        )->where('user_id', auth()->id())->getQuery();

        return $query;
    }

    /**
     * Paginasi dan transformasi data.
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
                $state = LetterStatus::make($status, new Letter());
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

    public function detailPage($id, $type)
    {
        // Mapping tipe ke route
        $routeMapping = [
            'Sistem Informasi & Data' => "history/information-system/$id",
            'Kehumasan' => "history/public-relation/$id",
        ];

        // Validasi tipe
        if (!array_key_exists($type, $routeMapping)) {
            throw new \InvalidArgumentException("Invalid type: {$type}");
        }

        // Redirect ke route yang sesuai
        return $this->redirect($routeMapping[$type], true);
    }

    // Reset pagination saat search berubah
    public function updatingSearchQuery()
    {
        $this->resetPage();
    }
}
