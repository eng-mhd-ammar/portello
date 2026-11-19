<?php

namespace Modules\Templates\V1\Services;

use Illuminate\Support\Str;
use Modules\Templates\Models\Template;
use Modules\Templates\V1\DTO\Template\UpsertTemplateDTO;
use Modules\Templates\V1\Repositories\TemplateRepository;

class TemplateService
{
    public function __construct(protected TemplateRepository $templateRepository) {}

    public function create(UpsertTemplateDTO $dto): Template
    {
        return Template::query()->create($this->attributesFromDto($dto));
    }

    public function update(Template $template, UpsertTemplateDTO $dto): Template
    {
        $template->forceFill($this->attributesFromDto($dto))->save();

        return $template->fresh();
    }

    /**
     * @return array<string, mixed>
     */
    public function templateProps(Template $template, bool $forAdmin = false): array
    {
        $sections = [];
        if (is_array($template->schema) && isset($template->schema['sections']) && is_array($template->schema['sections'])) {
            $sections = array_values($template->schema['sections']);
        }

        $props = [
            'id' => $template->id,
            'name' => $template->name,
            'slug' => $template->slug,
            'description' => $template->description,
            'preview_image' => $template->preview_image,
            'is_premium' => $template->is_premium,
            'price' => $template->price,
            'currency' => $template->currency,
            'is_active' => $template->is_active,
            'sort_order' => $template->sort_order,
            'sections' => $sections,
        ];

        if ($forAdmin) {
            $props['schema'] = $template->schema;
        }

        return $props;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attributesFromDto(UpsertTemplateDTO $dto): array
    {
        return [
            'name' => $dto->name,
            'slug' => $dto->slug !== '' ? $dto->slug : Str::slug($dto->name),
            'description' => $dto->description,
            'preview_image' => $dto->previewImage,
            'is_premium' => $dto->isPremium,
            'price' => $dto->price,
            'currency' => strtoupper($dto->currency),
            'is_active' => $dto->isActive,
            'sort_order' => $dto->sortOrder,
            'schema' => $dto->schema,
        ];
    }
}
