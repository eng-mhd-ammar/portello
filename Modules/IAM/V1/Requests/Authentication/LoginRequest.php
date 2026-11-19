<?php

namespace Modules\IAM\V1\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Modules\IAM\V1\Requests\Concerns\ValidatesRecaptcha;

class LoginRequest extends FormRequest
{
    use ValidatesRecaptcha;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
            ...$this->recaptchaRules(),
        ];
    }
}
