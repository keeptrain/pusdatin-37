<?php

namespace App\Providers;

use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Illuminate\Support\Facades\Gate;
use App\Policies\LetterPolicy;
use App\Policies\PublicRelationRequestPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super-Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('administrator')) {
                return true;
            }
        });

        // Letter Request Policy
        Gate::policy(Letter::class, LetterPolicy::class);

        // Public Relation Request Policy
        Gate::policy(PublicRelationRequest::class, PublicRelationRequestPolicy::class);

        Model::preventLazyLoading(! app()->isProduction());
    }
}
