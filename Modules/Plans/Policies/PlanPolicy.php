<?php

namespace Modules\Plans\Policies;

use Modules\IAM\Models\User;
use Modules\Plans\Models\Plan;

class PlanPolicy
{
    public function manage(User $user): bool
    {
        return $user->can('plans.manage');
    }

    public function update(User $user, Plan $plan): bool
    {
        return $user->can('plans.manage');
    }
}
