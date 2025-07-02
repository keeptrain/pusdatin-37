<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Services\Dashboard\AdminDashboardService;
use App\Services\Dashboard\UserDashboardService;

class DashboardDataService
{
    public function __construct(
        protected AdminDashboardService $adminDashboardService,
        protected UserDashboardService $userDashboardService,
    ) {
    }

    public function resolveDashboardView(User $user)
    {
        return $user->hasRole('user') ?
            $this->userDashboardService->render() :
            $this->adminDashboardService->render();
    }
}