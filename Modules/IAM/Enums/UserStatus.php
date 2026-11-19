<?php

namespace Modules\IAM\Enums;

use Modules\Core\Enums\Concerns\HasEnumLabels;

enum UserStatus: int
{
    use HasEnumLabels;

    case Inactive = 0;
    case Active = 1;
    case Suspended = -1;
    case Blocked = -2;

    public function label(): string
    {
        return ucfirst(strtolower($this->name));
    }

    public static function defaultValue(): int
    {
        return self::Active->value;
    }
}
