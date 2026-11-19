<?php

namespace Modules\Portfolios\Support;

use Modules\Portfolios\Models\Portfolio;

class CurrentPortfolio
{
    protected static ?Portfolio $portfolio = null;

    public static function set(Portfolio $portfolio): void
    {
        static::$portfolio = $portfolio;
    }

    public static function get(): ?Portfolio
    {
        return static::$portfolio;
    }

    public static function clear(): void
    {
        static::$portfolio = null;
    }
}
