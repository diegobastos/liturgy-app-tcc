<?php
namespace Src\Domain\Music\UseCases;

use Base\BaseUseCase;
use Src\Domain\Music\Music;

final class MusicFindByIdUseCase extends BaseUseCase
{
    public function __invoke(int $id): Music
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $item;
    }
}