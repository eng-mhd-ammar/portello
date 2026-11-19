<?php

namespace Modules\IAM\Exceptions;

use Illuminate\Validation\ValidationException;

class AuthException
{
    public static function invalidCredentials(): never
    {
        throw ValidationException::withMessages([
            'email' => 'Invalid credentials provided.',
        ]);
    }

    public static function accountHasBeenDeactivated(): never
    {
        throw ValidationException::withMessages([
            'email' => 'This account has been deactivated. Please contact support.',
        ]);
    }

    public static function unverifiedEmail(): never
    {
        throw ValidationException::withMessages([
            'email' => 'Please verify your email address before signing in.',
        ]);
    }
}
