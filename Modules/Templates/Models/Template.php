<?php

namespace Modules\Templates\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\RestoreUniqueObserver;
use Modules\Portfolios\Models\Portfolio;

#[ObservedBy([RestoreUniqueObserver::class])]
#[Fillable([
    'name',
    'slug',
    'description',
    'preview_image',
    'is_premium',
    'price',
    'currency',
    'is_active',
    'sort_order',
    'schema',
])]
class Template extends Model
{
    use SoftDeletes;

    public array $restoreUnique = ['slug'];

    protected function casts(): array
    {
        return [
            'is_premium' => 'boolean',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'schema' => 'array',
        ];
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }
}
