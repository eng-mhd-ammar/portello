<?php

namespace Modules\IAM\V1\Services;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Modules\IAM\Enums\IdentifierType;
use Modules\IAM\Enums\VerificationCodeType;
use Modules\IAM\Helpers\RandomCodeHelper;
use Modules\IAM\Models\User;
use Modules\IAM\Models\VerificationCode;
use Modules\IAM\Notifications\EmailVerificationCodeNotification;

class VerificationCodeService
{
    public function issueForEmail(User $user): VerificationCode
    {
        VerificationCode::query()
            ->where('user_id', $user->id)
            ->where('type', VerificationCodeType::EmailVerification)
            ->whereNull('verified_at')
            ->delete();

        $plainCode = RandomCodeHelper::digits(6);

        $verificationCode = VerificationCode::query()->create([
            'user_id' => $user->id,
            'code' => $plainCode,
            'identifier_type' => IdentifierType::Email,
            'identifier' => $user->email,
            'type' => VerificationCodeType::EmailVerification,
            'expired_at' => now()->addMinutes(15),
        ]);

        $user->notify(new EmailVerificationCodeNotification($plainCode));

        return $verificationCode;
    }

    public function resendForPendingUser(User $user): void
    {
        $key = 'verification-resend:'.$user->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'code' => "Please wait {$seconds} seconds before requesting another code.",
            ]);
        }

        RateLimiter::hit($key, 60);

        $this->issueForEmail($user);
    }

    public function verifyEmailCode(User $user, string $code): void
    {
        $verificationCode = VerificationCode::query()
            ->where('user_id', $user->id)
            ->where('type', VerificationCodeType::EmailVerification)
            ->whereNull('verified_at')
            ->where('expired_at', '>', now())
            ->latest('id')
            ->first();

        if ($verificationCode === null) {
            throw ValidationException::withMessages([
                'code' => 'This verification code has expired. Request a new one.',
            ]);
        }

        if ($verificationCode->attempts >= 5) {
            throw ValidationException::withMessages([
                'code' => 'Too many incorrect attempts. Request a new code.',
            ]);
        }

        if ($verificationCode->code !== $code) {
            $verificationCode->increment('attempts');

            throw ValidationException::withMessages([
                'code' => 'The verification code is incorrect.',
            ]);
        }

        $verificationCode->forceFill(['verified_at' => now()])->save();

        $user->forceFill(['email_verified_at' => now()])->save();
    }
}
