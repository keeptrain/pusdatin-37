<?php

namespace App\Http\Controllers;

use App\Models\Letters\Letter;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('roles');
        $userRoles = $user->roles()->pluck('id');

        return match (true) {
            $user->hasRole(['administrator', 'head_verifier', 'si_verifier', 'data_verifier', 'pr_verifier']) 
                => view('dashboard', [
                    'totalServices' => $this->totalRequestServices($userRoles)
                ]),
            $user->hasRole('user')
                 => view('dashboard-user'),
            default => abort(403),
        };
    }

    public function totalRequestServices($roles)
    {
        $query = Letter::query();

        if (!$roles->contains(2)) {
            $query->whereIn('current_division', $roles);
        }

        return $query->count();
    }

}
