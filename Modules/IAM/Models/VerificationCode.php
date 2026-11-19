<?php

namespace Modules\IAM\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\IAM\Enums\IdentifierType;
use Modules\IAM\Enums\VerificationCodeType;

#[Fillable(['user_id', 'code', 'identifier_type', 'identifier', 'type', 'expired_at', 'verified_at', 'attempts'])]
#[Hidden(['code'])]
class VerificationCode extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'identifier_type' => IdentifierType::class,
            'type' => VerificationCodeType::class,
            'expired_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
