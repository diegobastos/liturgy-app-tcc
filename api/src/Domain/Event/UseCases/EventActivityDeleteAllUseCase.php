<?php
namespace Src\Domain\Event\UseCases;

use Base\BaseUseCase;
use Src\Domain\Event\EventActivity;

final class EventActivityDeleteAllUseCase extends BaseUseCase
{
    public function __invoke(int $eventId): bool
    {
        return EventActivity::where('event_id', $eventId)->delete();
    }
}