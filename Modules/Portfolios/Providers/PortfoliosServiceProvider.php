<?php

namespace Modules\Portfolios\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Portfolios\Providers\V1\PortfolioModuleServiceProvider;

class PortfoliosServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(PortfolioModuleServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
    }
}
