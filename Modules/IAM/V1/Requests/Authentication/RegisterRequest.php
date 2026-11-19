<?php

namespace Modules\IAM\V1\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Rules\UniqueNotDeleted;
use Modules\IAM\Models\User;
use Modules\IAM\V1\Requests\Concerns\ValidatesRecaptcha;

class RegisterRequest extends FormRequest
{
    use ValidatesRecaptcha;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                new UniqueNotDeleted(User::class, 'email'),
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            ...$this->recaptchaRules(),
        ];
    }
}
