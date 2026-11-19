<?php

namespace Modules\Plans\Providers\V1;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Plans\Models\Plan;
use Modules\Plans\Policies\PlanPolicy;
use Modules\Plans\V1\Repositories\PlanRepository;
use Modules\Plans\V1\Services\PlanService;
use Modules\Plans\V1\Services\SubscriptionService;

class PlanModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(PlanRouteServiceProvider::class);
        $this->app->singleton(PlanRepository::class);
        $this->app->singleton(PlanService::class);
        $this->app->singleton(SubscriptionService::class);
    }

    public function boot(): void
    {
        Gate::policy(Plan::class, PlanPolicy::class);
    }
}
