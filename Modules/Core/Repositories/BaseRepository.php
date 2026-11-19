<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilderBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use LogicException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BaseRepository
{
    protected $model = Model::class;

    protected $query;

    protected $originalQuery;

    public function __construct($query = null)
    {
        $this->initQuery($query);
    }

    protected function initQuery($query = null): void
    {
        $allowedFilters = $this->allowedFilters();

        if ($this->usesSoftDeletes()) {
            array_unshift($allowedFilters, AllowedFilter::trashed());
        }

        $this->query = $query ?? QueryBuilder::for($this->model)
            ->allowedSorts('created_at', ...$this->allowedSorts())
            ->allowedFilters(...$allowedFilters)
            ->allowedFields(...$this->allowedFields())
            ->allowedIncludes(...$this->allowedIncludes())
            ->defaultSort('-created_at');

        $this->originalQuery = clone $this->query;
    }

    protected function usesSoftDeletes(): bool
    {
        return in_array(
            SoftDeletes::class,
            class_uses_recursive($this->getModel()) ?: [],
            true
        );
    }

    protected function ensureSoftDeletes(): void
    {
        if (! $this->usesSoftDeletes()) {
            throw new LogicException('The model does not use the SoftDeletes trait.');
        }
    }

    public function resetQuery(): static
    {
        $this->query = clone $this->originalQuery;

        return $this;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;
        $this->initQuery();

        return $this;
    }

    public function getModel(): string
    {
        return is_string($this->model)
            ? $this->model
            : get_class($this->model);
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query): static
    {
        $this->query = $query;

        return $this;
    }

    public function all(): Collection
    {
        return $this->query->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    public function show($modelId, array $with = []): Model
    {
        return $this->query->with($with)->findOrFail($modelId);
    }

    public function create(array $data): Model
    {
        $model = $this->getModel();

        return $model::create($data);
    }

    public function update(string $modelId, array $data): Model
    {
        $model = $this->query->findOrFail($modelId);
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete($modelId): void
    {
        $this->query->findOrFail($modelId)->delete();
    }

    public function deleteMany(array $ids): void
    {
        $this->query->whereIn('id', $ids)->delete();
    }

    public function forceDeleteMany(array $ids): void
    {
        $this->ensureSoftDeletes();
        $this->query->withTrashed()->whereIn('id', $ids)->forceDelete();
    }

    public function restoreMany(array $ids): void
    {
        $this->ensureSoftDeletes();
        $this->query->onlyTrashed()->whereIn('id', $ids)->restore();
    }

    public function forceDelete($modelId): void
    {
        $this->ensureSoftDeletes();
        $this->query->withTrashed()->findOrFail($modelId)->forceDelete();
    }

    public function restore($modelId): Model
    {
        $this->ensureSoftDeletes();
        $model = $this->query->onlyTrashed()->findOrFail($modelId);
        $model->restore();

        return $model;
    }

    public function switchBooleanColumn(int|string $modelId, string $column = 'is_active'): Model
    {
        $model = $this->query->findOrFail($modelId);
        $model->{$column} = ! $model->{$column};
        $model->save();

        return $model;
    }

    public function setEnumColumn(int|string $modelId, string $column, int|string $value): Model
    {
        $model = $this->query->findOrFail($modelId);
        $model->{$column} = $value;
        $model->save();

        return $model;
    }

    public function addScopes(array|string $scopes): static
    {
        if (is_string($scopes)) {
            $scopes = [$scopes => []];
        }

        foreach ($scopes as $scope => $arguments) {
            $this->query = $this->query->$scope(...$arguments);
        }

        return $this;
    }

    public function with(array|string $relations): static
    {
        $this->query = $this->query->with($relations);

        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->query = $this->query->orderBy($column, $direction);

        return $this;
    }

    public function where($column = null, $value = null, $operator = '='): static
    {
        if (is_string($column)) {
            $this->query = $this->query->where($column, $operator, $value);
        } elseif (is_callable($column)) {
            $this->query = $this->query->where($column);
        }

        return $this;
    }

    public function onlyTrashed(): static
    {
        $this->ensureSoftDeletes();
        $this->query = $this->query->onlyTrashed();

        return $this;
    }

    public function addQuery(callable $callback)
    {
        $result = $callback($this->query);

        if (
            $result instanceof EloquentBuilder
            || $result instanceof QueryBuilderBuilder
            || $result instanceof QueryBuilder
        ) {
            $this->query = $result;

            return $this;
        }

        return $result;
    }

    public function allowedSorts(): array
    {
        return [];
    }

    public function allowedFilters(): array
    {
        return [];
    }

    public function allowedIncludes(): array
    {
        return [];
    }

    public function allowedFields(): array
    {
        return [];
    }
}