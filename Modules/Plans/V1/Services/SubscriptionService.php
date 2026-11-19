<?php

namespace Modules\Plans\V1\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\IAM\Models\User;
use Modules\Plans\Enums\SubscriptionStatus;
use Modules\Plans\Models\Plan;
use Modules\Plans\Models\Subscription;
use Modules\Portfolios\Models\Portfolio;

class SubscriptionService
{
    public function activeForUser(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->where('status', SubscriptionStatus::Active)
            ->with('plan')
            ->latest('id')
            ->first();
    }

    public function switchPlan(User $user, Plan $plan): Subscription
    {
        if (! $plan->is_active) {
            throw ValidationException::withMessages([
                'plan_id' => 'This plan is not available.',
            ]);
        }

        $portfolioCount = Portfolio::query()
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->count();

        if ($portfolioCount > $plan->portfolio_limit) {
            throw ValidationException::withMessages([
                'plan_id' => 'You have more portfolios than this plan allows. Remove portfolios or choose a higher plan.',
            ]);
        }

        return DB::transaction(function () use ($user, $plan) {
            Subscription::query()
                ->where('user_id', $user->id)
                ->where('status', SubscriptionStatus::Active)
                ->update([
                    'status' => SubscriptionStatus::Canceled,
                    'canceled_at' => now(),
                ]);

            return Subscription::query()->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => SubscriptionStatus::Active,
                'starts_at' => now(),
                'ends_at' => null,
                'plan_snapshot' => $plan->snapshot(),
            ]);
        });
    }

    /**
     * @return array<string, mixed>|null
     */
    public function subscriptionProps(?Subscription $subscription): ?array
    {
        if ($subscription === null) {
            return null;
        }

        return [
            'id' => $subscription->id,
            'status' => $subscription->status->value,
            'status_label' => $subscription->status->label(),
            'starts_at' => $subscription->starts_at?->toIso8601String(),
            'plan' => $subscription->plan ? $this->planProps($subscription->plan) : null,
            'portfolio_limit' => $subscription->portfolioLimit(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function planProps(Plan $plan): array
    {
        return [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'description' => $plan->description,
            'price' => $plan->price,
            'currency' => $plan->currency,
            'interval' => $plan->interval,
            'portfolio_limit' => $plan->portfolio_limit,
            'custom_domain_enabled' => $plan->custom_domain_enabled,
            'remove_branding' => $plan->remove_branding,
            'is_active' => $plan->is_active,
            'sort_order' => $plan->sort_order,
        ];
    }
}
