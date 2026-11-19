<?php

namespace Modules\Plans\V1\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\Plans\Models\Plan;
use Modules\Plans\V1\DTO\Plan\UpsertPlanDTO;
use Modules\Plans\V1\Repositories\PlanRepository;
use Modules\Plans\V1\Requests\Plan\StorePlanRequest;
use Modules\Plans\V1\Requests\Plan\UpdatePlanRequest;
use Modules\Plans\V1\Services\PlanService;
use Modules\Plans\V1\Services\SubscriptionService;

class AdminPlanController extends BaseController
{
    public function __construct(
        protected PlanRepository $planRepository,
        protected PlanService $planService,
        protected SubscriptionService $subscriptionService,
    ) {}

    public function index(): Response
    {
        $this->authorize('manage', Plan::class);

        $plans = $this->planRepository->allForAdmin()->map(
            fn (Plan $plan) => $this->subscriptionService->planProps($plan)
        );

        return $this->inertia('Plans/Admin/Index', [
            'plans' => $plans,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('manage', Plan::class);

        return $this->inertia('Plans/Admin/Form', [
            'plan' => null,
        ]);
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        $this->authorize('manage', Plan::class);

        $this->planService->create(UpsertPlanDTO::fromRequest($request));

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan): Response
    {
        $this->authorize('update', $plan);

        return $this->inertia('Plans/Admin/Form', [
            'plan' => $this->subscriptionService->planProps($plan),
        ]);
    }

    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $this->authorize('update', $plan);

        $this->planService->update($plan, UpsertPlanDTO::fromRequest($request));

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }
}
