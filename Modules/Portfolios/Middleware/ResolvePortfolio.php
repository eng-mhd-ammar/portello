<?php

namespace Modules\Portfolios\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Portfolios\Support\CurrentPortfolio;
use Modules\Portfolios\V1\Controllers\PublicPortfolioController;
use Modules\Portfolios\V1\Services\PortfolioHostResolver;
use Symfony\Component\HttpFoundation\Response;

class ResolvePortfolio
{
    public function __construct(
        protected PortfolioHostResolver $hostResolver,
        protected PublicPortfolioController $publicPortfolioController,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        if ($this->hostResolver->isCentralHost($host)) {
            return $next($request);
        }

        $portfolio = $this->hostResolver->resolveFromRequest($request);

        if ($portfolio === null) {
            abort(404, 'Portfolio not found.');
        }

        CurrentPortfolio::set($portfolio);
        $request->attributes->set('portfolio', $portfolio);

        if ($request->isMethod('GET') && $request->is('/')) {
            return $this->publicPortfolioController->show($request, $portfolio);
        }

        abort(404);
    }
}
