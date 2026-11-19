<?php

namespace Modules\Core\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

class DefaultValue implements ValidationRule
{
    public function __construct(protected mixed $default = null) {}

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if ($value === null || $value === '') {
            $request = request()->all();
            Arr::set($request, $attribute, self::cast($this->default));
            request()->replace($request);
        }
    }

    public static function applyToValidator($validator, string $attribute, array $parameters): bool
    {
        $default = $parameters[0] ?? null;

        if ($default === null) {
            return true;
        }

        $data = $validator->getData();
        $current = data_get($data, $attribute);

        if ($current === null || $current === '') {
            $casted = self::cast($default);
            data_set($data, $attribute, $casted);
            $validator->setData($data);

            $requestData = request()->all();
            Arr::set($requestData, $attribute, $casted);
            request()->replace($requestData);
        }

        return true;
    }

    protected static function cast(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $lower = strtolower($value);

        return match ($lower) {
            'null' => null,
            'true' => true,
            'false' => false,
            default => ctype_digit($value) ? (int) $value : (is_numeric($value) ? (float) $value : $value),
        };
    }
}