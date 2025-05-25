<?php

namespace App\Http\Controllers;



use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\PublicRelationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {

        $user = auth()->user()->load('roles');
        $userRoles = $user->roles()->pluck('id');

        if ($user->hasRole('user')) {
            return view('dashboard-user');
        }

        $totalServices = 0;
        $totalPr = 0;

        if ($user->hasRole(['administrator', 'head_verifier'])) {
            $totalServices = $this->totalSiRequestServices() + $this->totalPrRequestServices();
            $totalPr = $this->totalPrRequestServices();
        } elseif ($user->hasRole(['si_verifier', 'data_verifier'])) {
            $totalServices = $this->totalSiRequestServices($userRoles);
        } elseif ($user->hasRole(['pr_verifier', 'promkes_verifier'])) {
            $totalServices = $this->totalPrRequestServices();
            $totalPr = $this->totalPrRequestServices();
        }

        $categoryPercentages = $this->calculateCategoryPercentages($totalPr);
        $siStatusCounts = $this->getSiDataStatusCounts($userRoles);

        if ($user->hasRole('head_verifier')) {
            $siStatusCounts = $this->getSiDataStatusCounts();
        }

        // untuk bar chart
        $year = now()->year;

        // Query: hitung jumlah Letter per bulan (1–12) di SQLite
        $counts = Letter::selectRaw("
                CAST(strftime('%m', created_at) AS integer) AS month,
                COUNT(*) AS total
            ")
            ->whereRaw("strftime('%Y', created_at) = ?", [$year])
            ->groupBy('month')
            ->pluck('total', 'month')    // menghasilkan [ month => total, … ]
            ->toArray();

        // Siapkan array bulan 1–12
        $months = range(1, 12);

        // Labels: nama bulan dalam format 'January', 'February', …
        $labels = array_map(
            fn($m) => Carbon::create()->month($m)->format('F'),
            $months
        );

        // Data: jika bulan tidak ada di $counts, isi 0
        $data = array_map(
            fn($m) => $counts[$m] ?? 0,
            $months
        );

        // untuk bar chart

        return view('dashboard', [
            'totalServices' => $totalServices,
            'categoryPercentages' => $categoryPercentages,
            'siStatusCounts' => $siStatusCounts,
            'labels'     => $labels,
            'data'     => $data,
        ]);
    }

    public function totalSiRequestServices($rolesId = null)
    {
        $query = Letter::select('id');

        if ($rolesId !== null) {
            $query->whereIn('current_division', $rolesId);
        }

        return $query->count('id');
    }

    public function totalPrRequestServices()
    {
        return Cache::remember('total_pr_requests', now()->addMinutes(10), function () {
            return PublicRelationRequest::count('id');
        });
    }

    private function getSiDataStatusCounts($currentDivision = null)
    {
        $statusStates = [
            'pending' => 'App\States\Pending',
            'disposition' =>  'App\States\Disposition',
            'process' =>  'App\States\Process',
            'replied' =>  'App\States\Replied',
            'approvedKasatpel' => 'App\States\ApprovedKasatpel',
            'approvedKapusdatin' => 'App\States\ApprovedKapusdatin',
        ];

        $query = Letter::select('status', DB::raw('COUNT(*) as total'))
            ->whereIn('status', array_values($statusStates))
            ->groupBy('status');

        if ($currentDivision !== null) {
            $query->where('current_division', $currentDivision);
        }

        $statusCounts = $query->pluck('total', 'status');

        return [
            'pending' => $statusCounts[$statusStates['pending']] ?? 0,
            'disposition' => $statusCounts[$statusStates['disposition']] ?? 0,
            'process' => $statusCounts[$statusStates['process']] ?? 0,
            'replied' => $statusCounts[$statusStates['replied']] ?? 0,
            'approvedKasatpel' => $statusCounts[$statusStates['approvedKasatpel']] ?? 0,
            'approvedKapusdatin' => $statusCounts[$statusStates['approvedKapusdatin']] ?? 0,
        ];
    }

    private function getPrStatusCounts()
    {
        return [
            'pending' => PublicRelationRequest::query()->where('status', 'pending')->count(),
            'completed' => PublicRelationRequest::query()->where('status', 'completed')->count(),
        ];
    }

    private function calculateCategoryPercentages($totalPr)
    {
        $totalsByDivision = Letter::select('current_division')
            ->whereIn('current_division', [3, 4])
            ->groupBy('current_division')
            ->selectRaw('current_division, COUNT(*) as total')
            ->pluck('total', 'current_division');

        $totalSi = $totalsByDivision[3] ?? 0; // Divisi 3 (Si)
        $totalData = $totalsByDivision[4] ?? 0; // Divisi 4 (Data)

        $total = $totalSi + $totalData + $totalPr;

        $percentages = [
            'si' => $total > 0 ? round(($totalSi / $total) * 100, 2) : 0,
            'data' => $total > 0 ? round(($totalData / $total) * 100, 2) : 0,
            'pr' => $total > 0 ? round(($totalPr / $total) * 100, 2) : 0,
        ];

        return $percentages;
    }
}
