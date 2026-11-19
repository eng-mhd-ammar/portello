<?php

namespace Modules\Plans\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Plans\Providers\V1\PlanModuleServiceProvider;
use Modules\Plans\V1\Services\SubscriptionProvisioner;

class PlansServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(PlanModuleServiceProvider::class);
        $this->app->singleton(SubscriptionProvisioner::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
    }
}
