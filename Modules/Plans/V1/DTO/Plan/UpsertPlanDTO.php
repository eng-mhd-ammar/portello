<?php

namespace Modules\Plans\V1\DTO\Plan;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\DTO\BaseDTO;
use Modules\Plans\V1\Requests\Plan\StorePlanRequest;
use Modules\Plans\V1\Requests\Plan\UpdatePlanRequest;

class UpsertPlanDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description,
        public string $price,
        public string $currency,
        public string $interval,
        public int $portfolioLimit,
        public bool $customDomainEnabled,
        public bool $removeBranding,
        public bool $isActive,
        public int $sortOrder,
    ) {}

    public static function fromRequest(FormRequest $request): static
    {
        /** @var StorePlanRequest|UpdatePlanRequest $request */
        return new self(
            name: $request->string('name')->toString(),
            slug: $request->string('slug')->toString(),
            description: $request->filled('description') ? $request->string('description')->toString() : null,
            price: $request->string('price')->toString(),
            currency: $request->string('currency')->toString(),
            interval: $request->string('interval')->toString(),
            portfolioLimit: $request->integer('portfolio_limit'),
            customDomainEnabled: $request->boolean('custom_domain_enabled'),
            removeBranding: $request->boolean('remove_branding'),
            isActive: $request->boolean('is_active'),
            sortOrder: $request->integer('sort_order'),
        );
    }
}
