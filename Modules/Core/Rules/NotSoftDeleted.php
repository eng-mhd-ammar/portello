<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotSoftDeleted implements ValidationRule
{
    public function __construct(protected string $model) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value) {
            return;
        }

        try {
            $this->model::query()->findOrFail($value);
        } catch (ModelNotFoundException) {
            $fail("The selected {$attribute} is invalid or has been deleted.");
        }
    }
}