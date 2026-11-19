<?php

namespace Modules\Plans\V1\Services;

use Illuminate\Support\Str;
use Modules\Plans\Models\Plan;
use Modules\Plans\V1\DTO\Plan\UpsertPlanDTO;
use Modules\Plans\V1\Repositories\PlanRepository;

class PlanService
{
    public function __construct(protected PlanRepository $planRepository) {}

    public function create(UpsertPlanDTO $dto): Plan
    {
        return Plan::query()->create($this->attributesFromDto($dto));
    }

    public function update(Plan $plan, UpsertPlanDTO $dto): Plan
    {
        $plan->forceFill($this->attributesFromDto($dto))->save();

        return $plan->fresh();
    }

    /**
     * @return array<string, mixed>
     */
    protected function attributesFromDto(UpsertPlanDTO $dto): array
    {
        return [
            'name' => $dto->name,
            'slug' => $dto->slug !== '' ? $dto->slug : Str::slug($dto->name),
            'description' => $dto->description,
            'price' => $dto->price,
            'currency' => strtoupper($dto->currency),
            'interval' => $dto->interval,
            'portfolio_limit' => $dto->portfolioLimit,
            'custom_domain_enabled' => $dto->customDomainEnabled,
            'remove_branding' => $dto->removeBranding,
            'is_active' => $dto->isActive,
            'sort_order' => $dto->sortOrder,
        ];
    }
}
