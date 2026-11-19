<?php

namespace Modules\IAM\Providers\V1\Auth;

use Illuminate\Support\ServiceProvider;
use Modules\IAM\V1\Services\AuthService;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AuthRouteServiceProvider::class);
        $this->app->singleton(AuthService::class);
    }
}
