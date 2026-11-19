<?php

namespace Modules\Plans\Enums;

use Modules\Core\Enums\Concerns\HasEnumLabels;

enum SubscriptionStatus: int
{
    use HasEnumLabels;

    case Pending = 0;
    case Active = 1;
    case Canceled = 2;
    case Expired = 3;

    public function label(): string
    {
        return ucfirst(strtolower($this->name));
    }

    public static function defaultValue(): int
    {
        return self::Active->value;
    }
}
