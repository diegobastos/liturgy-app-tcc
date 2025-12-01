<?php
namespace Src\Domain\Event\UseCases;

use Base\BaseUseCase;
use Src\Domain\Event\DTOs\ActivityDTO;
use Src\Domain\Event\EventActivity;

final class EventActivityUpdateUseCase extends BaseUseCase
{
    public function __invoke(int $id, ActivityDTO $dto): EventActivity
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $this->repository->update($id, $dto);
    }
}