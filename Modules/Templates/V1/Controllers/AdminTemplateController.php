<?php

namespace Modules\Templates\V1\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\Templates\Models\Template;
use Modules\Templates\V1\DTO\Template\UpsertTemplateDTO;
use Modules\Templates\V1\Repositories\TemplateRepository;
use Modules\Templates\V1\Requests\Template\StoreTemplateRequest;
use Modules\Templates\V1\Requests\Template\UpdateTemplateRequest;
use Modules\Templates\V1\Services\TemplateService;

class AdminTemplateController extends BaseController
{
    public function __construct(
        protected TemplateRepository $templateRepository,
        protected TemplateService $templateService,
    ) {}

    public function index(): Response
    {
        $this->authorize('manage', Template::class);

        $templates = $this->templateRepository->allForAdmin()->map(
            fn (Template $template) => $this->templateService->templateProps($template, forAdmin: true),
        );

        return $this->inertia('Templates/Admin/Index', [
            'templates' => $templates,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('manage', Template::class);

        return $this->inertia('Templates/Admin/Form', [
            'template' => null,
        ]);
    }

    public function store(StoreTemplateRequest $request): RedirectResponse
    {
        $this->authorize('manage', Template::class);

        $template = $this->templateService->create(UpsertTemplateDTO::fromRequest($request));

        return redirect()
            ->route('admin.templates.index')
            ->with('success', "Template \"{$template->name}\" created successfully.");
    }

    public function edit(Template $template): Response
    {
        $this->authorize('update', $template);

        return $this->inertia('Templates/Admin/Form', [
            'template' => $this->templateService->templateProps($template, forAdmin: true),
        ]);
    }

    public function update(UpdateTemplateRequest $request, Template $template): RedirectResponse
    {
        $this->authorize('update', $template);

        $this->templateService->update($template, UpsertTemplateDTO::fromRequest($request));

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Template updated successfully.');
    }
}
