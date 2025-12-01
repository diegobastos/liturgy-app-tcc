<?php

namespace Src\Domain\User\DTOs;

use Base\BaseDTO;

final class UpdateUserDTO extends BaseDTO 
{
    public ?string $name;
    public ?string $username;
    public ?string $email;
    public ?string $password;
    public ?bool $active = true;
    public ?string $roles = null;
    public ?string $timezone = 'America/Sao_Paulo';

    public function isEmpty(): bool
    {
        return empty($this->name) && empty($this->username) && empty($this->email);
    } 
}