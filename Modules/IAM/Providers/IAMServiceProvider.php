<?php

namespace Modules\IAM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\IAM\Providers\V1\Auth\AuthServiceProvider;
use Modules\IAM\Providers\V1\Profile\ProfileServiceProvider;

class IAMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(ProfileServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
    }
}
