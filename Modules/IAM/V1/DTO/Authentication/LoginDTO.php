<?php

namespace Modules\IAM\V1\DTO\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\DTO\BaseDTO;
use Modules\IAM\V1\Requests\Authentication\LoginRequest;

class LoginDTO extends BaseDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}

    public static function fromRequest(FormRequest $request): static
    {
        /** @var LoginRequest $request */
        return new self(
            email: $request->string('email')->lower()->toString(),
            password: $request->string('password')->toString(),
            remember: $request->boolean('remember'),
        );
    }
}
