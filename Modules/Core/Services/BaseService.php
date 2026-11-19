<?php

namespace Modules\Core\Services;

use Illuminate\Http\Request;
use Modules\Core\DTO\BaseDTO;
use Modules\Core\Exceptions\PaginationException;
use Modules\Core\Repositories\BaseRepository;

abstract class BaseService
{
    public function __construct(protected BaseRepository $repository) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function index()
    {
        $perPage = request()->integer('pageSize', 15);

        if ($perPage < 1) {
            PaginationException::invalidPerPageProvided();
        }

        if (request()->boolean('paginated', true)) {
            return $this->repository->paginate($perPage);
        }

        return $this->repository->all();
    }

    public function show(string $modelId)
    {
        return $this->repository->show($modelId);
    }

    public function create(BaseDTO $DTO)
    {
        return $this->repository->create($DTO->toArray());
    }

    public function update(string $modelId, BaseDTO $DTO)
    {
        return $this->repository->update($modelId, $DTO->filterNull()->toArray());
    }

    public function delete(string $modelId): void
    {
        $this->repository->delete($modelId);
    }

    public function deleteMany(array $ids): void
    {
        $this->repository->deleteMany($ids);
    }

    public function restore(string $modelId)
    {
        return $this->repository->restore($modelId);
    }

    public function forceDelete(string $modelId): void
    {
        $this->repository->forceDelete($modelId);
    }

    public function with(string|array $relations): static
    {
        $this->repository = $this->repository->with($relations);

        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->repository = $this->repository->orderBy($column, $direction);

        return $this;
    }

    protected function request(): Request
    {
        return request();
    }

    protected function validatePerPage(?int $perPage): int
    {
        if ($perPage === null || $perPage < 1) {
            PaginationException::invalidPerPageProvided();
        }

        return $perPage;
    }
}
