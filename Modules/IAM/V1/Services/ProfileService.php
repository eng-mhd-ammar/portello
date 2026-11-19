<?php

namespace Modules\IAM\V1\Services;

use Modules\IAM\Models\User;
use Modules\IAM\V1\DTO\Profile\UpdateProfileDTO;

class ProfileService
{
    public function __construct(protected VerificationCodeService $verificationCodeService) {}

    /**
     * @return array{user: User, email_changed: bool}
     */
    public function update(User $user, UpdateProfileDTO $dto): array
    {
        $emailChanged = strcasecmp($user->email, $dto->email) !== 0;

        $attributes = [
            'name' => $dto->name,
            'email' => $dto->email,
        ];

        if ($emailChanged) {
            $attributes['email_verified_at'] = null;
        }

        if ($dto->password !== null) {
            $attributes['password'] = $dto->password;
        }

        $user->forceFill($attributes)->save();

        if ($emailChanged) {
            $this->verificationCodeService->issueForEmail($user);
        }

        return [
            'user' => $user->fresh(),
            'email_changed' => $emailChanged,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function profilePayload(User $user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'email_verified' => $user->email_verified_at !== null,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'last_login_at' => $user->last_login_at?->toIso8601String(),
            'roles' => $user->getRoleNames()->values()->all(),
        ];
    }
}
