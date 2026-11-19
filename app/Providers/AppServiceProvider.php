<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Billing\Providers\BillingServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\IAM\Models\User;
use Modules\IAM\Providers\IAMServiceProvider;
use Modules\Plans\Providers\PlansServiceProvider;
use Modules\Portfolios\Providers\PortfoliosServiceProvider;
use Modules\Templates\Providers\TemplatesServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(CoreServiceProvider::class);
        $this->app->register(IAMServiceProvider::class);
        $this->app->register(PlansServiceProvider::class);
        $this->app->register(TemplatesServiceProvider::class);
        $this->app->register(PortfoliosServiceProvider::class);
        $this->app->register(BillingServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, string $ability): ?bool {
            if ($user instanceof User && $user->hasRole('admin')) {
                return true;
            }

            return null;
        });
    }
}
