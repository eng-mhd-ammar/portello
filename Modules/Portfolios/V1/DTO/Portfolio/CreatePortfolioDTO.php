<?php

namespace Modules\Portfolios\V1\DTO\Portfolio;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\DTO\BaseDTO;
use Modules\Portfolios\V1\Requests\Portfolio\StorePortfolioRequest;

class CreatePortfolioDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $slug,
        public int $templateId,
    ) {}

    public static function fromRequest(FormRequest $request): static
    {
        /** @var StorePortfolioRequest $request */
        $slug = $request->filled('slug')
            ? $request->string('slug')->toString()
            : $request->string('name')->toString();

        return new self(
            name: $request->string('name')->toString(),
            slug: $slug,
            templateId: $request->integer('template_id'),
        );
    }
}
