<?php

namespace Modules\Portfolios\V1\Requests\Portfolio;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Core\Rules\UniqueNotDeleted;
use Modules\Portfolios\Models\Portfolio;
use Modules\Templates\Models\Template;

class StorePortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('portfolios.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:63',
                'alpha_dash',
                'not_in:'.implode(',', config('portfolio.reserved_subdomains', [])),
                new UniqueNotDeleted(Portfolio::class, 'slug'),
            ],
            'template_id' => [
                'required',
                'integer',
                new NotSoftDeleted(Template::class),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('slug')) {
            $this->merge([
                'slug' => strtolower((string) $this->input('slug')),
            ]);
        }
    }
}
