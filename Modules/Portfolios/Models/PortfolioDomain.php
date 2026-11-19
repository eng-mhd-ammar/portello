<?php

namespace Modules\Portfolios\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\RestoreUniqueObserver;
use Modules\Portfolios\Enums\PortfolioDomainStatus;
use Modules\Portfolios\Enums\PortfolioDomainType;

#[ObservedBy([RestoreUniqueObserver::class])]
#[Fillable([
    'portfolio_id',
    'host',
    'type',
    'status',
    'verification_token',
    'verified_at',
    'last_checked_at',
    'failure_reason',
    'is_primary',
])]
class PortfolioDomain extends Model
{
    use SoftDeletes;

    public array $restoreUnique = ['host'];

    protected function casts(): array
    {
        return [
            'type' => PortfolioDomainType::class,
            'status' => PortfolioDomainStatus::class,
            'verified_at' => 'datetime',
            'last_checked_at' => 'datetime',
            'is_primary' => 'boolean',
        ];
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
