<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ProhibitedUnlessHasRole implements ValidationRule
{
    /** @param  array<int, string>|string  $roles */
    public function __construct(
        protected string|array $roles,
        protected mixed $defaultValue = null,
    ) {
        $this->roles = is_array($roles) ? $roles : [$roles];
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (
            is_scalar($this->defaultValue)
            && is_scalar($value)
            && (string) $this->defaultValue === (string) $value
        ) {
            return;
        }

        $user = Auth::user();

        if ($user === null) {
            $fail('Authentication is required.');

            return;
        }

        if (! method_exists($user, 'hasAnyRole')) {
            $fail('You are not allowed to change this field.');

            return;
        }

        if (! $user->hasAnyRole($this->roles)) {
            $fail('You are not allowed to change this field.');
        }
    }
}