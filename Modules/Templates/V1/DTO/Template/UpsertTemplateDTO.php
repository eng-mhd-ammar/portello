<?php

namespace Modules\Templates\V1\DTO\Template;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\DTO\BaseDTO;
use Modules\Templates\V1\Requests\Template\StoreTemplateRequest;
use Modules\Templates\V1\Requests\Template\UpdateTemplateRequest;

class UpsertTemplateDTO extends BaseDTO
{
    /**
     * @param  array<string, mixed>|null  $schema
     */
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description,
        public ?string $previewImage,
        public bool $isPremium,
        public string $price,
        public string $currency,
        public bool $isActive,
        public int $sortOrder,
        public ?array $schema,
    ) {}

    public static function fromRequest(FormRequest $request): static
    {
        /** @var StoreTemplateRequest|UpdateTemplateRequest $request */
        $schema = null;

        if ($request->filled('schema')) {
            $decoded = json_decode($request->string('schema')->toString(), true);
            $schema = is_array($decoded) ? $decoded : null;
        }

        return new self(
            name: $request->string('name')->toString(),
            slug: $request->string('slug')->toString(),
            description: $request->filled('description') ? $request->string('description')->toString() : null,
            previewImage: $request->filled('preview_image') ? $request->string('preview_image')->toString() : null,
            isPremium: $request->boolean('is_premium'),
            price: $request->string('price')->toString(),
            currency: $request->string('currency')->toString(),
            isActive: $request->boolean('is_active'),
            sortOrder: $request->integer('sort_order'),
            schema: $schema,
        );
    }
}
