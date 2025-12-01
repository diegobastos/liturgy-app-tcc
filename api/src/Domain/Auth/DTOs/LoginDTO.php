<?php

namespace Src\Domain\Auth\DTOs;

use Base\BaseDTO;

final class LoginDTO extends BaseDTO
{
    public ?string $username;
    public ?string $email;
    public ?string $password;
    
    public function isEmpty(): bool
    {
        return empty($this->username) && empty($this->email);
    }

    public function getUsernameEmail():string
    {
        return $this->username ?? $this->email ?? '';
    }
}