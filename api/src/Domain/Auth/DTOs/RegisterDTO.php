<?php

namespace Src\Domain\Auth\DTOs;

use Base\BaseDTO;

final class RegisterDTO extends BaseDTO
{
    public ?string $name;
    public ?string $email;
    public ?string $username;
    
    public function isEmpty(): bool
    {
        return empty($this->name) || empty($this->username) || empty($this->email);
    }

    public function getUsernameEmail():string
    {
        return $this->username ?? $this->email ?? '';
    }
}