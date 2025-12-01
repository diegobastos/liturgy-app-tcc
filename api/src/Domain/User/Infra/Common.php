<?php

namespace Src\Domain\User\Infra;

use Ramsey\Uuid\Uuid;
use Src\Domain\User\User;

final class Common
{
    public static function preparePassword(string $plainPassword): array
    {
        $salt = Uuid::uuid4()->toString();
        $hash = hash('sha256', $plainPassword . $salt);
        
        return [
            'salt' => $salt,
            'password_hash' => $hash,
        ];
    }

    public static function existsByEmail(string $email): ?User
    {
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            return null;
        }
    
        return $user;
    }
    
    public static function existsByUsername(string $username): ?User
    {
        $user = User::where('username', $username)->first();
    
        if (!$user) {
            return null;
        }
    
        return $user;
    }      
}