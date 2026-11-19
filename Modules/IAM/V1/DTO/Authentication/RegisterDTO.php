<?php

namespace Modules\IAM\V1\DTO\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\DTO\BaseDTO;
use Modules\IAM\V1\Requests\Authentication\RegisterRequest;

class RegisterDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(FormRequest $request): static
    {
        /** @var RegisterRequest $request */
        return new self(
            name: $request->string('name')->toString(),
            email: $request->string('email')->lower()->toString(),
            password: $request->string('password')->toString(),
        );
    }
}
