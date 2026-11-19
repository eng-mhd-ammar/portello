<?php

namespace Modules\Core\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrimRequestParameters
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->merge(
            collect($request->all())->map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            })->all()
        );

        return $next($request);
    }
}