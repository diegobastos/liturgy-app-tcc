<?php

namespace Base;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    protected $repository;
    public $userData;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->findAll();
    }

    public function getAllPaged(int $page = 1, int $limit = 10): LengthAwarePaginator
    {
        return $this->repository->findAllPaged($page, $limit);
    }    

    public function getById(int $id): ?Model
    {
        return $this->repository->findById($id);
    }

    public function create(BaseDTO $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(int $id, BaseDTO $data): ?Model
    {
        if (!$this->getById($id)) {
            throw new Exception('Item não encontrado!', 404);
        }     

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        if (!$this->repository->findById($id)) {
           throw new Exception('Item não encontrado!', 404); 
        }
        return $this->repository->delete($id);        
    }
}
