<?php
namespace Src\Domain\Music;

use Base\BaseRepository;
use Src\Domain\Music\Music;
use Illuminate\Database\Eloquent\Collection;

final class MusicRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Music()); // Passa o modelo Music para o repositório genérico
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
