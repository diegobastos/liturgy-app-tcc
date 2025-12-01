<?php
namespace Src\Domain\Event\UseCases;

use Base\BaseUseCase;
use Src\Domain\Event\DTOs\ActivityDTO;
use Src\Domain\Event\DTOs\CreateEventDTO;
use Src\Domain\Event\Event;
use Src\Domain\Event\EventActivityRepository;

final class EventCreateUseCase extends BaseUseCase
{
    public function __invoke(CreateEventDTO $dto): Event
    {
        $activities = $dto->activities ?? [];
        $event = $this->repository->create($dto);

        foreach ($activities as $a) {
            $act = new ActivityDTO($a);
            $act->event_id = $event->id;
            EventActivityCreateUseCase::getInstance(new EventActivityRepository())($act);
        }

        return $event->refresh();
    }
}