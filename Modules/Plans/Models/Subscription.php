<?php

namespace Modules\Plans\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\IAM\Models\User;
use Modules\Plans\Enums\SubscriptionStatus;

#[Fillable([
    'user_id',
    'plan_id',
    'order_id',
    'status',
    'starts_at',
    'ends_at',
    'canceled_at',
    'scheduled_plan_id',
    'plan_snapshot',
])]
class Subscription extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'canceled_at' => 'datetime',
            'plan_snapshot' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function scheduledPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'scheduled_plan_id');
    }

    public function portfolioLimit(): int
    {
        $snapshot = $this->plan_snapshot ?? [];

        return (int) ($snapshot['plan_portfolio_limit'] ?? $snapshot['portfolio_limit'] ?? 1);
    }
}
