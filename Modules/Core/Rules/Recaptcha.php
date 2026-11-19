<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! filled(config('recaptcha.secret_key')) || ! filled(config('recaptcha.site_key'))) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the reCAPTCHA verification.');

            return;
        }

        $secret = config('recaptcha.secret_key');

        $response = Http::asForm()
            ->timeout(10)
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

        if (! $response->successful()) {
            $fail('reCAPTCHA verification is temporarily unavailable. Please try again.');

            return;
        }

        $body = $response->json();

        if (! ($body['success'] ?? false)) {
            $fail('reCAPTCHA verification failed. Please try again.');
        }
    }
}
