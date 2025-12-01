<?php

namespace Src\Domain\Scale\DTOs;

use Base\BaseDTO;

class MemberDTO extends BaseDTO
{
    public ?string $uuid;
    public ?string $user_id;
    public ?string $role;
    public ?int $scale_id = null;
    public ?string $status = 'CONFIRMED'; //por enquanto não editar status

    public function isEmpty(): bool
    {
        return empty($this->user_id);
    }     
}