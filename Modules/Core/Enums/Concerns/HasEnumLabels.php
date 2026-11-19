<?php

namespace Modules\Core\Enums\Concerns;

trait HasEnumLabels
{
    public static function tableComment(): string
    {
        return collect(static::cases())
            ->map(fn ($case) => $case->value.' => '.$case->label())
            ->join(', ');
    }
}