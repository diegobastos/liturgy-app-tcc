<?php

namespace Src\Domain\Scale\DTOs;

use Base\BaseDTO;

class CreateScaleDTO extends BaseDTO
{
    public ?string $uuid;
    public ?string $start_at = null;
    public ?string $end_at = null;
    public ?int $event_id;
    public ?int $scale_type_id = null;
    public ?string $notes = null;

     /** @var MemberDTO[]|null */
    public ?array $members = null;

    public function isEmpty(): bool
    {
        return empty($this->name) && empty($this->start_at) && empty($this->end_at);
    }     
}