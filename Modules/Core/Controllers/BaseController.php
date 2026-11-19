<?php

namespace Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BaseController extends Controller
{
    protected function inertia(string $component, array $props = []): Response
    {
        return Inertia::render($component, $props);
    }

    protected function redirectWithSuccess(
        string $route,
        string $message,
        array $parameters = [],
    ): RedirectResponse {
        return redirect()
            ->route($route, $parameters)
            ->with('success', $message);
    }

    protected function backWithSuccess(string $message): RedirectResponse
    {
        return back()->with('success', $message);
    }
}