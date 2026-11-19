<?php

namespace Modules\Templates\Providers\V1;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Templates\Models\Template;
use Modules\Templates\Policies\TemplatePolicy;
use Modules\Templates\V1\Repositories\TemplateRepository;
use Modules\Templates\V1\Services\TemplateService;

class TemplateModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(TemplateRouteServiceProvider::class);
        $this->app->singleton(TemplateRepository::class);
        $this->app->singleton(TemplateService::class);
    }

    public function boot(): void
    {
        Gate::policy(Template::class, TemplatePolicy::class);
    }
}
