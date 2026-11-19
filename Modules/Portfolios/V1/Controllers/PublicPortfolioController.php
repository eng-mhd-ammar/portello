<?php

namespace Modules\Portfolios\V1\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\Portfolios\Models\Portfolio;

class PublicPortfolioController extends BaseController
{
    public function show(Request $request, Portfolio $portfolio): Response
    {
        $portfolio->loadMissing(['template', 'user']);

        return $this->inertia('Portfolios/Public/Show', [
            'site' => [
                'name' => $portfolio->name,
                'slug' => $portfolio->slug,
                'headline' => $portfolio->content['headline'] ?? $portfolio->name,
                'bio' => $portfolio->content['bio'] ?? null,
                'show_branding' => (bool) $portfolio->show_branding,
                'template' => $portfolio->template?->only(['name', 'slug']),
                'owner' => $portfolio->user?->only(['name']),
                'host' => $request->getHost(),
            ],
        ]);
    }
}
