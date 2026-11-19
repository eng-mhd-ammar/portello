<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnumRule implements ValidationRule
{
    /** @param  class-string  $enum */
    public function __construct(protected string $enum) {}

    /** @param  class-string  $enum  */
    public static function make(string $enum): self
    {
        return new self($enum);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $allowed = array_map(
            fn ($case) => $case->value,
            $this->enum::cases()
        );

        if (! in_array(is_numeric($value) ? (int) $value : $value, $allowed, true)) {
            $fail('The selected :attribute is invalid.');
        }
    }
}