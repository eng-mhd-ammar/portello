<?php

namespace Modules\IAM\V1\DTO\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\DTO\BaseDTO;
use Modules\IAM\V1\Requests\Profile\UpdateProfileRequest;

class UpdateProfileDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
    ) {}

    public static function fromRequest(FormRequest $request): static
    {
        /** @var UpdateProfileRequest $request */
        $password = $request->filled('password')
            ? $request->string('password')->toString()
            : null;

        return new self(
            name: $request->string('name')->toString(),
            email: $request->string('email')->lower()->toString(),
            password: $password,
        );
    }
}
