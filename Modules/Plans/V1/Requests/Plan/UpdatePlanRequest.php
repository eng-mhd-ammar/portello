<?php

namespace Modules\Plans\V1\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\UniqueNotDeleted;
use Modules\Plans\Models\Plan;

class UpdatePlanRequest extends FormRequest
{
    use ValidatesPlanFields;

    public function authorize(): bool
    {
        return $this->user()?->can('plans.manage') ?? false;
    }

    public function rules(): array
    {
        /** @var Plan $plan */
        $plan = $this->route('plan');

        return $this->planFieldRules(new UniqueNotDeleted(Plan::class, 'slug', (string) $plan->getKey()));
    }
}
