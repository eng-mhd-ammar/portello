<?php

namespace Modules\IAM\Enums;

use Modules\Core\Enums\Concerns\HasEnumLabels;

enum IdentifierType: int
{
    use HasEnumLabels;

    case Email = 1;
    case Phone = 2;

    public function label(): string
    {
        return ucfirst(strtolower($this->name));
    }

    public static function defaultValue(): int
    {
        return self::Email->value;
    }
}
