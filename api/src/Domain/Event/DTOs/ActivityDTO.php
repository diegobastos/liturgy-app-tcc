<?php

namespace Src\Domain\Event\DTOs;

use Base\BaseDTO;

class ActivityDTO extends BaseDTO
{
    public ?string $uuid;
    public ?int $event_id;
    public ?int $music_id;
    public int $position = 1;
    public ?string $notes = null;

    public function isEmpty(): bool
    {
        return empty($this->event_id);
    }     
}