<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Rules\DefaultValue;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Validator::extendImplicit(
            'default',
            fn ($attribute, $value, $parameters, $validator) => DefaultValue::applyToValidator(
                $validator,
                $attribute,
                $parameters
            )
        );
    }
}