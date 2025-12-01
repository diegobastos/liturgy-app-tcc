<?php
namespace Src\Domain\Event\UseCases;

use Base\BaseUseCase;
use Src\Domain\Event\Event;

final class EventFindByIdUseCase extends BaseUseCase
{
    public function __invoke(int $id): Event
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $item;
    }
}