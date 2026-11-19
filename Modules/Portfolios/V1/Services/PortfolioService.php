<?php

namespace Modules\Portfolios\V1\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\IAM\Models\User;
use Modules\Plans\Enums\SubscriptionStatus;
use Modules\Portfolios\Enums\PortfolioDomainStatus;
use Modules\Portfolios\Enums\PortfolioDomainType;
use Modules\Portfolios\Enums\PortfolioStatus;
use Modules\Portfolios\Models\Portfolio;
use Modules\Portfolios\Models\PortfolioDomain;
use Modules\Portfolios\V1\DTO\Portfolio\CreatePortfolioDTO;
use Modules\Templates\Models\Template;

class PortfolioService
{
    public function create(User $user, CreatePortfolioDTO $dto): Portfolio
    {
        $this->assertWithinPlanLimit($user);

        $template = Template::query()
            ->whereKey($dto->templateId)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->firstOrFail();

        if ($template->is_premium) {
            throw ValidationException::withMessages([
                'template_id' => 'Premium templates require a purchase or upgrade.',
            ]);
        }

        $slug = Str::slug($dto->slug ?: $dto->name);

        $baseDomain = (string) config('portfolio.base_domain');
        $subdomainHost = $slug.'.'.$baseDomain;

        return DB::transaction(function () use ($user, $dto, $template, $slug, $subdomainHost) {
            $portfolio = Portfolio::query()->create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'name' => $dto->name,
                'slug' => $slug,
                'status' => PortfolioStatus::Draft,
                'show_branding' => true,
                'content' => [
                    'headline' => $dto->name,
                    'bio' => 'Welcome to my portfolio.',
                ],
            ]);

            PortfolioDomain::query()->create([
                'portfolio_id' => $portfolio->id,
                'host' => $subdomainHost,
                'type' => PortfolioDomainType::Subdomain,
                'status' => PortfolioDomainStatus::Active,
                'verified_at' => now(),
                'is_primary' => true,
            ]);

            return $portfolio->load(['template', 'domains']);
        });
    }

    public function publish(Portfolio $portfolio): Portfolio
    {
        if ($portfolio->user_id !== auth()->id() && ! auth()->user()?->hasRole('admin')) {
            abort(403);
        }

        $portfolio->forceFill([
            'status' => PortfolioStatus::Published,
            'published_at' => $portfolio->published_at ?? now(),
        ])->save();

        return $portfolio->fresh(['template', 'domains']);
    }

    protected function assertWithinPlanLimit(User $user): void
    {
        $subscription = $user->subscriptions()
            ->where('status', SubscriptionStatus::Active)
            ->latest('id')
            ->first();

        $limit = $subscription?->portfolioLimit() ?? 1;
        $count = Portfolio::query()
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->count();

        if ($count >= $limit) {
            throw ValidationException::withMessages([
                'name' => 'You have reached the portfolio limit for your current plan.',
            ]);
        }
    }

    public static function publicUrl(Portfolio $portfolio): ?string
    {
        $host = $portfolio->domains()
            ->where('is_primary', true)
            ->whereNull('deleted_at')
            ->value('host');

        if (! $host) {
            return null;
        }

        $scheme = parse_url((string) config('app.url'), PHP_URL_SCHEME) ?: 'http';

        return $scheme.'://'.$host;
    }
}
