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
        $lettersQuery = Letter::select(
            'id',
            DB::raw("'Sistem Informasi & Data' as type"),
            'status',
            'created_at',
            'active_revision'
        )->where('user_id', auth()->id());

        $publicRelationRequestsQuery = PublicRelationRequest::select(
            'id',
            DB::raw("'Kehumasan' as type"),
            'status',
            'created_at',
            DB::raw("null as active_revision"),
        )->where('user_id', auth()->id());

        // Gabungkan kedua query dengan UNION ALL
        $combinedQuery = $lettersQuery->unionAll($publicRelationRequestsQuery);

        // Dapatkan SQL dan binding dari query gabungan
        $sql = $combinedQuery->toSql();
        $combinedQuery->getBindings();

        // Query untuk paginasi
        $mainQuery = DB::table(DB::raw("($sql) as combined_table"))
            ->mergeBindings($combinedQuery->getQuery())
            ->orderBy('created_at', 'desc');

        // Paginasi menggunakan metode Livewire
        $paginator = $mainQuery->paginate($this->perPage);

        // Transformasi setiap item dalam koleksi
        $transformedCollection = $paginator->getCollection()->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'type' => $item->type,
                'status' => $this->getStatusLabel($item->type, $item->status),
                'created_at' => Carbon::parse($item->created_at)->format('d F Y, H:m'),
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
