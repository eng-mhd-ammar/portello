<?php

namespace Modules\Portfolios\V1\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\Portfolios\Models\Portfolio;
use Modules\Portfolios\V1\DTO\Portfolio\CreatePortfolioDTO;
use Modules\Portfolios\V1\Repositories\PortfolioRepository;
use Modules\Portfolios\V1\Requests\Portfolio\StorePortfolioRequest;
use Modules\Portfolios\V1\Services\PortfolioService;
use Modules\Templates\Models\Template;

class PortfolioController extends BaseController
{
    public function __construct(
        protected PortfolioService $portfolioService,
        protected PortfolioRepository $portfolioRepository,
    ) {}

    public function index(): Response
    {
        $this->authorize('viewAny', Portfolio::class);

        $portfolios = $this->portfolioRepository
            ->forUser((int) auth()->id())
            ->map(fn (Portfolio $portfolio) => $this->portfolioProps($portfolio));

        return $this->inertia('Portfolios/Index', [
            'portfolios' => $portfolios,
            'baseDomain' => config('portfolio.base_domain'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Portfolio::class);

        $templates = Template::query()
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'description', 'is_premium'])
            ->map(fn (Template $template) => [
                'id' => $template->id,
                'name' => $template->name,
                'slug' => $template->slug,
                'description' => $template->description,
                'is_premium' => $template->is_premium,
            ]);

        $initialTemplateId = request()->integer('template_id') ?: null;

        if ($initialTemplateId && ! $templates->contains('id', $initialTemplateId)) {
            $initialTemplateId = null;
        }

        return $this->inertia('Portfolios/Create', [
            'templates' => $templates,
            'baseDomain' => config('portfolio.base_domain'),
            'initialTemplateId' => $initialTemplateId,
        ]);
    }

    public function store(StorePortfolioRequest $request): RedirectResponse
    {
        $this->authorize('create', Portfolio::class);

        $portfolio = $this->portfolioService->create(
            $request->user(),
            CreatePortfolioDTO::fromRequest($request),
        );

        return redirect()
            ->route('portfolios.index')
            ->with('success', "Portfolio \"{$portfolio->name}\" created.");
    }

    public function publish(Portfolio $portfolio): RedirectResponse
    {
        $this->authorize('update', $portfolio);

        $this->portfolioService->publish($portfolio);

        return back()->with('success', 'Portfolio published successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function portfolioProps(Portfolio $portfolio): array
    {
        $primaryHost = $portfolio->domains->firstWhere('is_primary', true)?->host;

        return [
            'id' => $portfolio->id,
            'name' => $portfolio->name,
            'slug' => $portfolio->slug,
            'status' => $portfolio->status->value,
            'status_label' => $portfolio->status->label(),
            'published_at' => $portfolio->published_at?->toIso8601String(),
            'primary_host' => $primaryHost,
            'public_url' => $primaryHost
                ? ((parse_url((string) config('app.url'), PHP_URL_SCHEME) ?: 'http').'://'.$primaryHost)
                : null,
            'template' => $portfolio->template?->only(['id', 'name', 'slug']),
        ];
    }
}
