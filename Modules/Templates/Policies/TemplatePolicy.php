<?php

namespace Modules\Templates\Policies;

use Modules\IAM\Models\User;
use Modules\Templates\Models\Template;

class TemplatePolicy
{
    public function manage(User $user): bool
    {
        return $user->can('templates.manage');
    }

    public function update(User $user, Template $template): bool
    {
        return $user->can('templates.manage');
    }
}
