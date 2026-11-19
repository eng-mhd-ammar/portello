<?php

namespace Modules\Portfolios\Enums;

use Modules\Core\Enums\Concerns\HasEnumLabels;

enum PortfolioDomainStatus: int
{
    use HasEnumLabels;

    case Pending = 0;
    case Active = 1;
    case Failed = -1;

    public function label(): string
    {
        return ucfirst(strtolower($this->name));
    }

    public static function defaultValue(): int
    {
        return self::Pending->value;
    }
}
