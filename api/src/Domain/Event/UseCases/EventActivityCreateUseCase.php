<?php
namespace Src\Domain\Event\UseCases;

use Base\BaseUseCase;
use Src\Domain\Event\DTOs\ActivityDTO;
use Src\Domain\Event\EventActivity;

final class EventActivityCreateUseCase extends BaseUseCase
{
    public function __invoke(ActivityDTO $dto): EventActivity
    {      
        return $this->repository->create($dto);
    }
}