<?php

namespace Modules\Portfolios\Enums;

use Modules\Core\Enums\Concerns\HasEnumLabels;

enum PortfolioDomainType: int
{
    use HasEnumLabels;

    case Subdomain = 1;
    case Custom = 2;

    public function label(): string
    {
        return ucfirst(strtolower($this->name));
    }

    public static function defaultValue(): int
    {
        return self::Subdomain->value;
    }
}
