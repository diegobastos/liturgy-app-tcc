<?php

namespace Src\Domain\Event\DTOs;

use Base\BaseDTO;

class CreateEventDTO extends BaseDTO
{
    public ?string $uuid;
    public ?string $name;
    public ?string $start_at = null;
    public ?string $end_at = null;

     /** @var ActivityDTO[]|null */
    public ?array $activities = null;    

    public function isEmpty(): bool
    {
        return empty($this->name) && empty($this->start_at) && empty($this->end_at);
    }     
}