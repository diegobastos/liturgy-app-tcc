<?php
namespace Src\Domain\Event\UseCases;

use Base\BaseUseCase;
use Src\Domain\Event\DTOs\ActivityDTO;
use Src\Domain\Event\DTOs\UpdateEventDTO;
use Src\Domain\Event\Event;
use Src\Domain\Event\EventActivity;
use Src\Domain\Event\EventActivityRepository;

final class EventUpdateUseCase extends BaseUseCase
{
    public function __invoke(int $id, UpdateEventDTO $dto): Event
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        $dto->uuid = $item->uuid;
        $item = $this->repository->update($id, $dto);
        $activities = $dto->activities ?? [];
        
        try {
            EventActivityDeleteAllUseCase::getInstance(new EventActivityRepository())($item->id);
        } catch (\Exception $e) {}
        foreach ($activities as $a) { 
            $act = new ActivityDTO($a);
            $act->event_id = $item->id;
            EventActivityCreateUseCase::getInstance()($act);
        }           

        return $item->refresh();
    }
}