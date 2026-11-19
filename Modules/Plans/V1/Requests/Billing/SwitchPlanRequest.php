<?php

namespace Modules\Plans\V1\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Plans\Models\Plan;

class SwitchPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('billing.index') ?? false;
    }

    public function rules(): array
    {
        return [
            'plan_id' => ['required', 'integer', new NotSoftDeleted(Plan::class)],
        ];
    }
}
