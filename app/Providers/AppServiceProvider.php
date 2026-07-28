<?php

namespace App\Providers;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Service;
use App\Policies\PlaceCategoryPolicy;
use App\Policies\PlacePolicy;
use App\Policies\ServicePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::policy(Place::class, PlacePolicy::class);
        Gate::policy(PlaceCategory::class, PlaceCategoryPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
