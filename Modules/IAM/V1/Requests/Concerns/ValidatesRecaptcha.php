<?php

namespace Modules\IAM\V1\Requests\Concerns;

use Modules\Core\Rules\Recaptcha;

trait ValidatesRecaptcha
{
    /**
     * @return array<string, array<int, mixed>>
     */
    protected function recaptchaRules(): array
    {
        if (! filled(config('recaptcha.secret_key')) || ! filled(config('recaptcha.site_key'))) {
            return [];
        }

        return [
            'g-recaptcha-response' => ['required', 'string', new Recaptcha],
        ];
    }
}
