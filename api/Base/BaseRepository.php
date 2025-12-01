<?php

namespace Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findAll(): Collection
    {
        return $this->model::all();
    }

    public function findById(int|string $id): ?Model
    {
        $entity = is_numeric($id)
            ? $this->model::find((int) $id)
            : $this->model::where('uuid', $id)->first();
        return $entity;
    }

    abstract public function findByCriteria(array $criteria = []): Collection;

    public function findAllPaged(int $page = 1, int $limit = 10): LengthAwarePaginator
    {
        return $this->model::paginate($limit, ['*'], 'page', $page);
    }

    public function create(object $data): Model
    {
        return $this->handleExceptions(function () use ($data) {
            $data->uuid = Uuid::uuid7()->toString();
            return $this->model::create($data->toArray());
        });
    }

    public function update(int|string $id, object $data): ?Model
    {
        return $this->handleExceptions(function () use ($id, $data) {
            $entity = is_numeric($id)
                ? $this->model::find((int) $id)
                : $this->model::where('uuid', $id)->first();

            if (!$entity)
                return null;

            $entity->update($data->toArray());
            $entity->refresh();

            return $entity;
        });
    }

    public function delete(int|string $id): bool
    {
        return $this->handleExceptions(function () use ($id) {
            $entity = is_numeric($id)
                ? $this->model::find((int) $id)
                : $this->model::where('uuid', $id)->first();

            return $entity ? (bool) $entity->delete() : false;
        });
    }


    protected function handleExceptions(callable $callback)
    {
        try {
            return $callback();
        } catch (\PDOException $e) {
            // erros do banco
            $code = (int) $e->getCode();
            $status = match ($code) {
                '23000' => 409, // Integrity constraint violation
                '23503' => 409, // FK violation (Postgres)
                '23505' => 409, // Unique violation (Postgres)
                default => 500,
            };

            throw new \RuntimeException($e->getMessage(), $status, $e);
        } catch (\Exception $e) {
            // outros erros genéricos
            throw new \RuntimeException($e->getMessage(), 500, $e);
        }
    }
}
