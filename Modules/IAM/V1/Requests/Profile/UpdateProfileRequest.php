<?php

namespace Modules\IAM\V1\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Rules\UniqueNotDeleted;
use Modules\IAM\Models\User;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $userId = (string) $this->user()->getKey();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                new UniqueNotDeleted(User::class, 'email', $userId),
            ],
            'current_password' => ['required_with:password', 'current_password:web'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }
}
