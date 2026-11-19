<?php

namespace Modules\Templates\V1\Controllers;

use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\Templates\Models\Template;
use Modules\Templates\V1\Repositories\TemplateRepository;
use Modules\Templates\V1\Services\TemplateService;

class TemplateCatalogController extends BaseController
{
    public function __construct(
        protected TemplateRepository $templateRepository,
        protected TemplateService $templateService,
    ) {}

    public function index(): Response
    {
        $templates = $this->templateRepository->catalog()->map(
            fn (Template $template) => $this->templateService->templateProps($template),
        );

        return $this->inertia('Templates/Catalog/Index', [
            'templates' => $templates,
        ]);
    }

    public function show(Template $template): Response
    {
        abort_unless(
            $template->is_active || request()->user()?->can('templates.manage'),
            404,
        );

        return $this->inertia('Templates/Catalog/Show', [
            'template' => $this->templateService->templateProps($template),
            'canManage' => request()->user()?->can('templates.manage') ?? false,
        ]);
    }
}
