<?php

namespace Modules\Templates\V1\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\BaseRepository;
use Modules\Templates\Models\Template;

class TemplateRepository extends BaseRepository
{
    protected $model = Template::class;

    /**
     * @return array<int, string>
     */
    protected function allowedFilters(): array
    {
        return ['is_active', 'is_premium', 'slug'];
    }

    /**
     * @return Collection<int, Template>
     */
    public function catalog(): Collection
    {
        return Template::query()
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, Template>
     */
    public function allForAdmin(): Collection
    {
        return Template::query()
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
