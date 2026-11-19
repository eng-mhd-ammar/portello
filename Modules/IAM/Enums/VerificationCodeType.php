<?php

namespace Modules\IAM\Enums;

use Modules\Core\Enums\Concerns\HasEnumLabels;

enum VerificationCodeType: int
{
    use HasEnumLabels;

    case EmailVerification = 1;
    case PhoneVerification = 2;
    case PasswordReset = 3;

    public function label(): string
    {
        return ucfirst(strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $this->name) ?? $this->name));
    }

    public static function defaultValue(): int
    {
        return self::EmailVerification->value;
    }
}
