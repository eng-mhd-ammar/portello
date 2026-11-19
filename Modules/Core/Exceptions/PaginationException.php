<?php

namespace Modules\Core\Exceptions;

class PaginationException extends BaseException
{
    public static function invalidPerPageProvided(): never
    {
        throw new self('Page size must be greater than 0.', 422);
    }
}