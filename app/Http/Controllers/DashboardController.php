<?php

namespace App\Http\Controllers;

use App\Services\ZipServices;
use App\Services\Dashboard\DashboardDataService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardDataService $dashboardService,
    ) {
    }

    public function index(Request $request)
    {
        return $this->dashboardService->resolveDashboardView($request->user());
    }

    public function downloadSopAndTemplates()
    {
        try {
            return new ZipServices()->downloadSopAndTemplates();
        } catch (\Exception $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }
}