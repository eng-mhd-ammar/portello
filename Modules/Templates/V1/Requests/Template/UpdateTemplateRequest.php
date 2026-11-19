<?php

namespace Modules\Templates\V1\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\UniqueNotDeleted;
use Modules\Templates\Models\Template;

class UpdateTemplateRequest extends FormRequest
{
    use ValidatesTemplateFields;

    public function authorize(): bool
    {
        return $this->user()?->can('templates.manage') ?? false;
    }

    public function rules(): array
    {
        /** @var Template $template */
        $template = $this->route('template');

        return $this->templateFieldRules(new UniqueNotDeleted(Template::class, 'slug', (string) $template->getKey()));
    }
}
