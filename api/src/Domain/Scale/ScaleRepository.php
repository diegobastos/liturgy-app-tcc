<?php
namespace Src\Domain\Scale;

use Base\BaseRepository;
use Src\Domain\Scale\Scale;
use Illuminate\Database\Eloquent\Collection;

final class ScaleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Scale()); // Passa o modelo Scale para o repositório genérico
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
