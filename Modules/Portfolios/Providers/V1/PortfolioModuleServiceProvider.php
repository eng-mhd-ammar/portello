<?php

namespace Modules\Portfolios\Providers\V1;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Portfolios\Models\Portfolio;
use Modules\Portfolios\Policies\PortfolioPolicy;
use Modules\Portfolios\V1\Repositories\PortfolioRepository;
use Modules\Portfolios\V1\Services\PortfolioHostResolver;
use Modules\Portfolios\V1\Services\PortfolioService;

class PortfolioModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(PortfolioRouteServiceProvider::class);
        $this->app->singleton(PortfolioHostResolver::class);
        $this->app->singleton(PortfolioService::class);
        $this->app->singleton(PortfolioRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Portfolio::class, PortfolioPolicy::class);
    }
}
