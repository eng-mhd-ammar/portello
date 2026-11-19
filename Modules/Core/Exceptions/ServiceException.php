<?php

namespace Modules\Core\Exceptions;

class ServiceException extends BaseException
{
    public static function relationNotFound(string $relation): never
    {
        throw new self("Call to undefined relation {$relation}.", 500);
    }

    public static function forbidden(string $message = 'This action is unauthorized.'): never
    {
        throw new self($message, 403);
    }
}