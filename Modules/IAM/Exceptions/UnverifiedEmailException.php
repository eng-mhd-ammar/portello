<?php

namespace Modules\IAM\Exceptions;

use Modules\IAM\Models\User;

class UnverifiedEmailException extends \RuntimeException
{
    public function __construct(public User $user)
    {
        parent::__construct('Email address is not verified.');
    }
}
