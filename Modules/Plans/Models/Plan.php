<?php

namespace Modules\Plans\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\RestoreUniqueObserver;

#[ObservedBy([RestoreUniqueObserver::class])]
#[Fillable([
    'name',
    'slug',
    'description',
    'price',
    'currency',
    'interval',
    'portfolio_limit',
    'custom_domain_enabled',
    'remove_branding',
    'is_active',
    'sort_order',
])]
class Plan extends Model
{
    use SoftDeletes;

    public array $restoreUnique = ['slug'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'custom_domain_enabled' => 'boolean',
            'remove_branding' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function snapshot(): array
    {
        return [
            'plan_name' => $this->name,
            'plan_slug' => $this->slug,
            'plan_price' => $this->price,
            'plan_currency' => $this->currency,
            'plan_interval' => $this->interval,
            'plan_portfolio_limit' => $this->portfolio_limit,
            'plan_custom_domain_enabled' => $this->custom_domain_enabled,
            'plan_remove_branding' => $this->remove_branding,
        ];
    }
}
