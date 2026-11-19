<?php

namespace Modules\Plans\V1\Services;

use Illuminate\Support\Facades\DB;
use Modules\IAM\Models\User;
use Modules\Plans\Enums\SubscriptionStatus;
use Modules\Plans\Models\Plan;
use Modules\Plans\Models\Subscription;

class SubscriptionProvisioner
{
    public function provisionFree(User $user): Subscription
    {
        $plan = Plan::query()
            ->where('slug', 'free')
            ->whereNull('deleted_at')
            ->firstOrFail();

        return DB::transaction(function () use ($user, $plan) {
            Subscription::query()
                ->where('user_id', $user->id)
                ->where('status', SubscriptionStatus::Active)
                ->update(['status' => SubscriptionStatus::Canceled]);

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
}
