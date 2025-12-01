<?php

namespace Src\Domain\User\DTOs;

use Base\BaseDTO;

class CreateUserDTO extends BaseDTO
{
    public ?string $uuid;
    public ?string $name;
    public ?string $username;
    public ?string $email;
    public ?string $password;
    public ?string $salt = '';
    public ?string $password_hash = '';
    public ?string $roles = null;
    public ?bool $active = true;
    public ?string $timezone = 'America/Sao_Paulo';

    public function isEmpty(): bool
    {
        return empty($this->name) && empty($this->username) && empty($this->email);
    }      
}