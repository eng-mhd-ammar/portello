<?php

namespace Modules\Portfolios\V1\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\BaseRepository;
use Modules\Portfolios\Models\Portfolio;

class PortfolioRepository extends BaseRepository
{
    protected $model = Portfolio::class;

    /**
     * @return array<int, string>
     */
    protected function allowedFilters(): array
    {
        return ['user_id', 'status', 'slug'];
    }

    /**
     * @return array<int, string>
     */
    protected function allowedIncludes(): array
    {
        return ['template', 'domains', 'user'];
    }

    public function forUser(int $userId): Collection
    {
        return Portfolio::query()
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->with(['template', 'domains'])
            ->orderByDesc('created_at')
            ->get();
    }
}
