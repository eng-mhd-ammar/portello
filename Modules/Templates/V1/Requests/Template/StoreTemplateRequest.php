<?php

namespace Modules\Templates\V1\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\UniqueNotDeleted;
use Modules\Templates\Models\Template;

class StoreTemplateRequest extends FormRequest
{
    use ValidatesTemplateFields;

    public function authorize(): bool
    {
        return $this->user()?->can('templates.manage') ?? false;
    }

    public function rules(): array
    {
        return $this->templateFieldRules(new UniqueNotDeleted(Template::class, 'slug'));
    }
}
