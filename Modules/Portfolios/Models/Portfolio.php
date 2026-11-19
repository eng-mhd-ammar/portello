<?php

namespace Modules\Portfolios\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\RestoreUniqueObserver;
use Modules\IAM\Models\User;
use Modules\Portfolios\Enums\PortfolioStatus;
use Modules\Templates\Models\Template;

#[ObservedBy([RestoreUniqueObserver::class])]
#[Fillable([
    'user_id',
    'template_id',
    'name',
    'slug',
    'status',
    'published_at',
    'show_branding',
    'content',
    'theme',
    'blocks',
])]
class Portfolio extends Model
{
    use SoftDeletes;

    public array $restoreUnique = ['slug'];

    protected function casts(): array
    {
        return [
            'status' => PortfolioStatus::class,
            'published_at' => 'datetime',
            'show_branding' => 'boolean',
            'content' => 'array',
            'theme' => 'array',
            'blocks' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function domains(): HasMany
    {
        return $this->hasMany(PortfolioDomain::class);
    }

    public function primaryDomain(): ?PortfolioDomain
    {
        return $this->domains()
            ->where('is_primary', true)
            ->whereNull('deleted_at')
            ->first();
    }

    public function isPublished(): bool
    {
        return $this->status === PortfolioStatus::Published;
    }
}
