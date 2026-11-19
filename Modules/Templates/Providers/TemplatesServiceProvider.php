<?php

namespace Modules\Templates\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Templates\Providers\V1\TemplateModuleServiceProvider;

class TemplatesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(TemplateModuleServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
    }
}
