<?php
namespace Src\Domain\User;

use Base\BaseRepository;
use Src\Domain\User\User;
use Illuminate\Database\Eloquent\Collection;

final class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User()); // Passa o modelo User para o repositório genérico
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
