<?php

namespace Modules\IAM\V1\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\IAM\Enums\UserStatus;
use Modules\IAM\Exceptions\AuthException;
use Modules\IAM\Exceptions\UnverifiedEmailException;
use Modules\IAM\Models\User;
use Modules\IAM\V1\DTO\Authentication\LoginDTO;
use Modules\IAM\V1\DTO\Authentication\RegisterDTO;
use Modules\Plans\V1\Services\SubscriptionProvisioner;

class AuthService
{
    public function __construct(
        protected SubscriptionProvisioner $subscriptionProvisioner,
        protected VerificationCodeService $verificationCodeService,
    ) {}

    public function login(LoginDTO $DTO): User
    {
        $user = User::query()->where('email', $DTO->email)->first();

        if ($user === null || ! Auth::validate([
            'email' => $DTO->email,
            'password' => $DTO->password,
        ])) {
            AuthException::invalidCredentials();
        }

        if ($user->status !== UserStatus::Active) {
            AuthException::accountHasBeenDeactivated();
        }

        if ($user->email_verified_at === null) {
            $this->verificationCodeService->issueForEmail($user);

            throw new UnverifiedEmailException($user);
        }

        Auth::login($user, $DTO->remember);

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ])->save();

        return $user;
    }

    public function register(RegisterDTO $DTO): User
    {
        return DB::transaction(function () use ($DTO) {
            $user = User::query()->create([
                'name' => $DTO->name,
                'email' => $DTO->email,
                'password' => $DTO->password,
                'status' => UserStatus::Active,
                'email_verified_at' => null,
            ]);

            $user->assignRole('user');
            $this->subscriptionProvisioner->provisionFree($user);
            $this->verificationCodeService->issueForEmail($user);

            return $user;
        });
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
