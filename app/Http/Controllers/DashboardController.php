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
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('id');

        // Redirect user dashboard if the role is 'user'
        if ($user->hasRole('user')) {
            return view('dashboard-user', [
                'meetingList' => $this->getMeetingList()
            ]);
        }

        // Fetch data based on user roles
        $data = $this->fetchDashboardData($user, $userRoles);
        $monthlySiData = $this->getMonthlySiVerifierData(now()->year);
        $monthlyDataDiv = $this->getMonthlyDataDiv(now()->year);

        // Pass data to the view
        return view('dashboard', [
            'label' => $data['label'],
            'totalServices' => $data['totalServices'],
            'totalPr' => $data['totalPr'],
            'widthPercentage' => $this->calculateWidthPercentage($data['totalServices'], $data['totalPr']),
            'categoryPercentages' => $this->calculateCategoryPercentages($data['totalPr']),
            'statusCounts' => $data['statusCounts'],
            'monthlyLetterData' => $this->getMonthlyLetterData($userRoles),
            'monthlySiData' => $monthlySiData,
            'monthlyDataDiv' => $monthlyDataDiv,
        ]);
    }

    public function getMeetingList()
    {
        return Letter::getNearMeetingsByDate();
    }

    /**
     * Fetch dashboard data based on user roles.
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Support\Collection $userRoles
     * @return array
     */
    private function fetchDashboardData($user, $userRoles)
    {
        if ($user->hasRole(['administrator', 'head_verifier'])) {
            return $this->getDataForHeadVerifier();
        } elseif ($user->hasRole(['si_verifier', 'data_verifier'])) {
            return $this->getDataForSiVerifierOrDataVerifier($user, $userRoles);
        } elseif ($user->hasRole(['pr_verifier', 'promkes_verifier'])) {
            return $this->getDataForPrVerifierOrPromkesVerifier();
        } else if ($user->hasRole('promkes_verifier')) {
            return $this->getDataForPromkesVerifier();
        }

        // Default fallback
        return [
            'totalServices' => 0,
            'totalPr' => 0,
            'statusCounts' => [],
        ];
    }

    /**
     * Get data for administrator or head verifier.
     *
     * @return array
     */
    private function getDataForHeadVerifier()
    {
        $totalPrRequest = $this->totalPrRequestServices();

        return [
            'label' => 'Sistem Informasi & Data serta Kehumasan',
            'totalServices' => $this->totalRequestServices(
                $this->totalSiRequestServices(),
                $totalPrRequest
            ),
            'totalPr' => $totalPrRequest,
            'statusCounts' => $this->getCombinedStatusCounts(),
        ];
    }

    /**
     * Get data for SI verifier or data verifier.
     *
     * @param \Illuminate\Support\Collection $userRoles
     * @return array
     */
    private function getDataForSiVerifierOrDataVerifier($user, $userRoles)
    {
        if ($user->hasRole('si_verifier')) {
            $label = 'Sistem Informasi';
        } elseif ($user->hasRole('data_verifier')) {
            $label = 'Pengelolaan Data';
        }

        return [
            'label' => $label,
            'totalServices' => $this->totalSiRequestServices($userRoles),
            'totalPr' => 0,
            'needVerification' => 'asd',
            'statusCounts' => $this->getSiDataStatusCounts($userRoles),
        ];
    }

    /**
     * Get data for PR verifier or promkes verifier.
     *
     * @return array
     */
    private function getDataForPrVerifierOrPromkesVerifier()
    {
        $totalPr = $this->totalPrRequestServices();

        return [
            'label' => 'Kehumasan',
            'totalServices' => $totalPr,
            'totalPr' => $totalPr,
            'statusCounts' => $this->getPrStatusCounts('pr'),
        ];
    }

    private function getDataForPromkesVerifier()
    {
        $totalPr = $this->totalPrRequestServices();

        return [
            'label' => 'Perlu Kurasi',
            'totalServices' => $totalPr,
            'totalPr' => $totalPr,
            'statusCounts' => $this->getPrStatusCounts('pr'),
        ];
    }

    /**
     * Calculate width percentage.
     *
     * @param int $totalServices
     * @param int $totalPr
     * @return float
     */
    private function calculateWidthPercentage($totalServices, $totalPr)
    {
        return $totalServices > 0 ? ($totalPr / $totalServices) * 100 : 0;
    }

    private function totalSiRequestServices($rolesId = null)
    {
        return Letter::getTotalRequestsByRole($rolesId);
    }

    private function totalPrRequestServices()
    {
        return Cache::remember('total_pr_requests', now()->addMinutes(10), function () {
            return PublicRelationRequest::count('id');
        });
    }

    private function totalRequestServices($siRequest, $prRequest)
    {
        return $siRequest + $prRequest;
    }

    private function getSiDataStatusCounts($currentDivision = null)
    {
        $statusStates = [
            'pending' => 'App\States\Pending',
            'disposition' => 'App\States\Disposition',
            'process' => 'App\States\Process',
            'replied' => 'App\States\Replied',
            'approvedKasatpel' => 'App\States\ApprovedKasatpel',
            'repliedKapusdatin' => 'App\States\RepliedKapusdatin',
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
            'repliedKapusdatin' => $statusCounts[$statusStates['repliedKapusdatin']] ?? 0,
            'completed' => $statusCounts[$statusStates['approvedKapusdatin']] ?? 0,
        ];
    }

    private function getPrStatusCounts(?string $user = null)
    {
        $statusStates = [
            'pending' => 'App\States\PublicRelation\Pending',
            'promkesQueue' => 'App\States\PublicRelation\PromkesQueue',
            'promkesCompleted' => 'App\States\PublicRelation\PromkesComplete',
            'pusdatinQueue' => 'App\States\PublicRelation\PusdatinQueue',
            'pusdatinProcess' => 'App\States\PublicRelation\PusdatinProcess',
            'completed' => 'App\States\PublicRelation\Completed',
        ];

        $query = PublicRelationRequest::select('status', DB::raw('COUNT(*) as total'))
            ->whereIn('status', array_values($statusStates))
            ->groupBy('status');

        $statusCounts = $query->pluck('total', 'status');

        if ($user == 'pr') {
            return [
                'pending' => $statusCounts[$statusStates['pending']] ?? 0,
                'promkesQueue' => $statusCounts[$statusStates['promkesQueue']] ?? 0,
                'promkesCompleted' => $statusCounts[$statusStates['promkesCompleted']] ?? 0,
                'pusdatinQueue' => $statusCounts[$statusStates['pusdatinQueue']] ?? 0,
                'pusdatinProcess' => $statusCounts[$statusStates['pusdatinProcess']] ?? 0,
                'completed' => $statusCounts[$statusStates['completed']] ?? 0,
            ];
        } else {
            return [
                'promkesCompleted' => $statusCounts[$statusStates['promkesCompleted']] ?? 0,
                'completed' => $statusCounts[$statusStates['completed']] ?? 0,
            ];
        }
    }

    /**
     * Combine status counts from SI and PR.
     *
     * @return array
     */
    private function getCombinedStatusCounts()
    {
        $siStatusCounts = $this->getSiDataStatusCounts();

        $prStatusCounts = $this->getPrStatusCounts();

        // Gabungkan status counts dari SI dan PR
        return [
            'pending' => ($siStatusCounts['pending'] ?? 0) + ($prStatusCounts['promkesCompleted'] ?? 0),
            'completed' => ($siStatusCounts['completed'] ?? 0) + ($prStatusCounts['completed'] ?? 0),
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
            'si' => $total > 0 ? round(($totalSi / $total) * 100, 0) : 0,
            'data' => $total > 0 ? round(($totalData / $total) * 100, 0) : 0,
            'pr' => $total > 0 ? round(($totalPr / $total) * 100, 0) : 0,
        ];

        return $percentages;
    }

    private function getMonthlyLetterData($userRoles = null)
    {
        $user = auth()->user();

        // Query untuk data Letter dengan pemisahan berdasarkan current_division
        $letterQuery = Letter::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            'current_division'
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->whereIn('current_division', [3, 4]) // Filter hanya untuk current_division 3 dan 4
            ->groupBy(DB::raw('MONTH(created_at)'), 'current_division')
            ->orderBy('month');

        // Filter berdasarkan role user
        if (!$user->hasRole('head_verifier') && $userRoles !== null) {
            $letterQuery->whereIn('current_division', $userRoles);
        }

        $monthlyLetterData = $letterQuery->get();

        // Pisahkan data berdasarkan current_division
        $informationSystemDivisionData = [];
        $dataDivisionData = [];

        foreach ($monthlyLetterData as $record) {
            $month = (int) $record->month;
            if ($record->current_division === 3) {
                $informationSystemDivisionData[$month] = $record->total;
            } elseif ($record->current_division === 4) {
                $dataDivisionData[$month] = $record->total;
            }
        }

        // Query untuk data PublicRelationRequest
        $prQuery = PublicRelationRequest::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month');

        $monthlyPrData = $prQuery->pluck('total', 'month');

        // Buat array untuk 12 bulan dengan nilai default 0
        $informationSystemCounts = [];
        $dataCounts = [];
        $publicRelationCounts = [];

        for ($i = 1; $i <= 12; $i++) {
            $informationSystemCounts[] = $informationSystemDivisionData[$i] ?? 0;
            $dataCounts[] = $dataDivisionData[$i] ?? 0;
            $publicRelationCounts[] = $monthlyPrData[$i] ?? 0;
        }

        return [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'informationSystem' => $informationSystemCounts, // Data untuk current_division 3
            'data' => $dataCounts,                          // Data untuk current_division 4
            'publicRelation' => $publicRelationCounts      // Data untuk PublicRelationRequest
        ];
    }

    private function getMonthlySiVerifierData(int $year): array
    {
        // Query data sistem informasi
        $letterCounts = Letter::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', $year)
            ->where('current_division', 3)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Labels 12 bulan
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Isi array data dengan default 0 untuk bulan tanpa record
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = $letterCounts[$m] ?? 0;
        }

        return [
            'months' => $months,
            'letterData' => $data,
        ];
    }

    private function getMonthlyDataDiv(int $year): array
    {
        // Query data permohonan data
        $letterCounts = Letter::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', $year)
            ->where('current_division', 4)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Labels 12 bulan
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Isi array data dengan default 0 untuk bulan tanpa record
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = $letterCounts[$m] ?? 0;
        }

        return [
            'months' => $months,
            'letterData' => $data,
        ];
    }
}
