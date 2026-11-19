<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueNotDeleted implements ValidationRule
{
    public function __construct(
        protected string $model,
        protected string|array $columns,
        protected ?string $ignoreId = null,
        protected bool $softDeleted = true,
    ) {
        $this->ignoreId ??= request()->route('modelId')
            ?? request()->route('portfolio')
            ?? request()->route('id');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = $this->model::query();

        if ($this->softDeleted) {
            $query->whereNull('deleted_at');
        }

        $columns = is_array($this->columns) ? $this->columns : [$this->columns];

        $values = count($columns) === 1
            ? [$columns[0] => $value]
            : request()->only($columns);

        foreach ($values as $column => $columnValue) {
            if ($columnValue !== null) {
                $query->where($column, $columnValue);
            }
        }

        if ($this->ignoreId !== null) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail("The {$attribute} has already been taken.");
        }
    }
}