<?php

namespace Modules\Portfolios\Enums;

use Modules\Core\Enums\Concerns\HasEnumLabels;

enum PortfolioStatus: int
{
    use HasEnumLabels;

    case Draft = 0;
    case Published = 1;

    public function label(): string
    {
        return ucfirst(strtolower($this->name));
    }

    public static function defaultValue(): int
    {
        return self::Draft->value;
    }
}
