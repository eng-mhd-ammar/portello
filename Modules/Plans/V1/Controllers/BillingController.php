<?php

namespace Modules\Plans\V1\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\Plans\Models\Plan;
use Modules\Plans\V1\Repositories\PlanRepository;
use Modules\Plans\V1\Requests\Billing\SwitchPlanRequest;
use Modules\Plans\V1\Services\SubscriptionService;

class BillingController extends BaseController
{
    public function __construct(
        protected PlanRepository $planRepository,
        protected SubscriptionService $subscriptionService,
    ) {}

    public function index(): Response
    {
        $user = request()->user();
        $subscription = $this->subscriptionService->activeForUser($user);

        $plans = $this->planRepository->catalog()->map(
            fn (Plan $plan) => $this->subscriptionService->planProps($plan)
        );

        return $this->inertia('Plans/Billing/Index', [
            'subscription' => $this->subscriptionService->subscriptionProps($subscription),
            'plans' => $plans,
        ]);
    }

    public function switch(SwitchPlanRequest $request): RedirectResponse
    {
        $plan = Plan::query()->findOrFail($request->integer('plan_id'));

        $this->subscriptionService->switchPlan($request->user(), $plan);

        return back()->with('success', "Your subscription is now on the {$plan->name} plan.");
    }
}
