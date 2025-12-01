<?php
namespace Src\Domain\Event;

use Base\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

final class EventActivityRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new EventActivity()); // Passa o modelo Event para o repositório genérico
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
