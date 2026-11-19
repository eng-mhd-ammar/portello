<?php

namespace Modules\Plans\V1\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\BaseRepository;
use Modules\Plans\Models\Plan;

class PlanRepository extends BaseRepository
{
    protected $model = Plan::class;

    /**
     * @return array<int, string>
     */
    protected function allowedFilters(): array
    {
        return ['is_active', 'slug'];
    }

    /**
     * @return Collection<int, Plan>
     */
    public function catalog(): Collection
    {
        return Plan::query()
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, Plan>
     */
    public function allForAdmin(): Collection
    {
        return Plan::query()
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
