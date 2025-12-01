<?php
namespace Src\Domain\Music\UseCases;

use Base\BaseUseCase;
use Src\Domain\Music\DTOs\UpdateMusicDTO;
use Src\Domain\Music\Music;

final class MusicUpdateUseCase extends BaseUseCase
{
    public function __invoke(int $id, UpdateMusicDTO $dto): Music
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $this->repository->update($id, $dto);
    }
}