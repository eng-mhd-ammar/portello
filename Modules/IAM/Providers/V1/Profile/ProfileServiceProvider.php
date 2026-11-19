<?php

namespace Modules\IAM\Providers\V1\Profile;

use Illuminate\Support\ServiceProvider;
use Modules\IAM\V1\Services\ProfileService;

class ProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(ProfileRouteServiceProvider::class);
        $this->app->singleton(ProfileService::class);
    }
}
