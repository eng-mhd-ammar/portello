<?php

namespace Modules\Portfolios\V1\Services;

use Illuminate\Http\Request;
use Modules\Portfolios\Enums\PortfolioDomainStatus;
use Modules\Portfolios\Enums\PortfolioStatus;
use Modules\Portfolios\Models\Portfolio;
use Modules\Portfolios\Models\PortfolioDomain;

class PortfolioHostResolver
{
    public function isCentralHost(string $host): bool
    {
        $host = strtolower($host);

        return in_array($host, config('portfolio.central_hosts', []), true);
    }

    public function resolveFromRequest(Request $request): ?Portfolio
    {
        $host = strtolower($request->getHost());

        if ($this->isCentralHost($host)) {
            return null;
        }

        $portfolio = $this->resolveByCustomDomain($host);

        if ($portfolio !== null) {
            return $portfolio;
        }

        return $this->resolveBySubdomain($host);
    }

    protected function resolveByCustomDomain(string $host): ?Portfolio
    {
        $domain = PortfolioDomain::query()
            ->where('host', $host)
            ->where('status', PortfolioDomainStatus::Active)
            ->whereNotNull('verified_at')
            ->whereNull('deleted_at')
            ->whereHas('portfolio', function ($query) {
                $query
                    ->where('status', PortfolioStatus::Published)
                    ->whereNull('deleted_at');
            })
            ->with(['portfolio.template', 'portfolio.user'])
            ->first();

        return $domain?->portfolio;
    }

    protected function resolveBySubdomain(string $host): ?Portfolio
    {
        $baseDomain = strtolower((string) config('portfolio.base_domain'));

        $suffix = '.'.$baseDomain;

        if (! str_ends_with($host, $suffix) || $host === $baseDomain) {
            return null;
        }

        $subdomain = substr($host, 0, -strlen($suffix));

        if ($subdomain === '' || in_array($subdomain, config('portfolio.reserved_subdomains', []), true)) {
            return null;
        }

        $domain = PortfolioDomain::query()
            ->where('host', $host)
            ->where('status', PortfolioDomainStatus::Active)
            ->whereNull('deleted_at')
            ->whereHas('portfolio', function ($query) {
                $query
                    ->where('status', PortfolioStatus::Published)
                    ->whereNull('deleted_at');
            })
            ->with(['portfolio.template', 'portfolio.user'])
            ->first();

        if ($domain !== null) {
            return $domain->portfolio;
        }

        return Portfolio::query()
            ->where('slug', $subdomain)
            ->where('status', PortfolioStatus::Published)
            ->whereNull('deleted_at')
            ->with(['template', 'user'])
            ->first();
    }
}
