<?php

namespace Modules\Core\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class RestoreUniqueObserver
{
    public function restoring(Model $model): void
    {
        if (! property_exists($model, 'restoreUnique')) {
            return;
        }

        foreach ($model->restoreUnique as $uniqueColumns) {
            $columns = (array) $uniqueColumns;
            $query = $model->newQuery();

            foreach ($columns as $column) {
                $value = $model->getAttribute($column);

                if ($value === null) {
                    continue 2;
                }

                $query->where($column, $value);
            }

            $query->where($model->getKeyName(), '!=', $model->getKey());

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    $columns[0] => [
                        'Cannot restore this record because a record with the same unique values already exists.',
                    ],
                ]);
            }
        }
    }
}