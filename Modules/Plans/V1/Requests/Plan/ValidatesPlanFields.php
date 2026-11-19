<?php

namespace Modules\Plans\V1\Requests\Plan;

use Modules\Core\Rules\UniqueNotDeleted;

trait ValidatesPlanFields
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'custom_domain_enabled' => $this->boolean('custom_domain_enabled'),
            'remove_branding' => $this->boolean('remove_branding'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function planFieldRules(UniqueNotDeleted $slugRule): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', $slugRule],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'currency' => ['required', 'string', 'size:3'],
            'interval' => ['required', 'string', 'max:16'],
            'portfolio_limit' => ['required', 'integer', 'min:1', 'max:1000'],
            'custom_domain_enabled' => ['boolean'],
            'remove_branding' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ];
    }
}
