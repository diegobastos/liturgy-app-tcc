<?php
namespace Src\Domain\Event;

use Base\BaseRepository;
use Src\Domain\Event\Event;
use Illuminate\Database\Eloquent\Collection;

final class EventRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Event()); // Passa o modelo Event para o repositório genérico
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
