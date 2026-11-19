<?php

namespace Modules\Templates\V1\Requests\Template;

use Closure;
use Modules\Core\Rules\UniqueNotDeleted;

trait ValidatesTemplateFields
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_premium' => $this->boolean('is_premium'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function templateFieldRules(UniqueNotDeleted $slugRule): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', $slugRule],
            'description' => ['nullable', 'string', 'max:5000'],
            'preview_image' => ['nullable', 'string', 'max:2048'],
            'is_premium' => ['boolean'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'currency' => ['required', 'string', 'size:3'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'schema' => ['nullable', 'string', 'max:50000', $this->validJsonObject()],
        ];
    }

    protected function validJsonObject(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if ($value === null || $value === '') {
                return;
            }

            if (! is_string($value)) {
                $fail('Schema must be a JSON string.');

                return;
            }

            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                $fail('Schema must be valid JSON object.');
            }
        };
    }
}
