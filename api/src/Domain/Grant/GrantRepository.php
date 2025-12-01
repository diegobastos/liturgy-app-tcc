<?php
namespace Src\Domain\Grant;

use Base\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

final class GrantRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Grant()); // Passa o modelo User para o repositório genérico
    }

    public function findByCriteria(array $criteria = []): Collection {
        /** 
        * Exemplo de uso
        */
        //return User::where($criteria)->with('posts')->get();
        throw new \InvalidArgumentException("Criteria array cannot be empty.");
    }

    //specific methods here
}
