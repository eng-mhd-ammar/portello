<?php

namespace Modules\Portfolios\Policies;

use Modules\IAM\Models\User;
use Modules\Portfolios\Models\Portfolio;

class PortfolioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('portfolios.index');
    }

    public function view(User $user, Portfolio $portfolio): bool
    {
        return $user->can('portfolios.show') && ($user->id === $portfolio->user_id || $user->hasRole('admin'));
    }

    public function create(User $user): bool
    {
        return $user->can('portfolios.create');
    }

    public function update(User $user, Portfolio $portfolio): bool
    {
        return $user->can('portfolios.update') && ($user->id === $portfolio->user_id || $user->hasRole('admin'));
    }

    public function delete(User $user, Portfolio $portfolio): bool
    {
        return $user->can('portfolios.delete') && ($user->id === $portfolio->user_id || $user->hasRole('admin'));
    }
}
