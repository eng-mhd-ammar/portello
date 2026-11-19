<?php

namespace Modules\Core\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Exceptions\ServiceException;
use Spatie\QueryBuilder\Filters\Filter;

class FilterOwner implements Filter
{
    /** @var array<int, string> */
    protected array $columns;

    /** @param  array<int, string>|string  $columns */
    public function __construct(string|array $columns = 'user_id')
    {
        $this->columns = is_array($columns) ? $columns : [$columns];
    }

    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        $items = is_array($value) ? $value : [$value];

        foreach ($items as $item) {
            if (in_array(strtolower((string) $item), ['0', 'false', 'all'], true)) {
                if (method_exists($user, 'hasRole') && ! $user->hasRole('admin')) {
                    ServiceException::forbidden('Only administrators can list all records.');
                }

                return;
            }
        }

        $query->where(function ($q) use ($user) {
            foreach ($this->columns as $index => $column) {
                if ($index === 0) {
                    $q->where($column, $user->id);
                } else {
                    $q->orWhere($column, $user->id);
                }
            }
        });
    }
}