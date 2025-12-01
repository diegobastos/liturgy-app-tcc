<?php

namespace Src\Domain\Auth\Common;

class PasswordService {
    public static function generateSalt($length = 16) {
        return bin2hex(random_bytes($length));
    }

    public static function hashPassword(string $password): string {
        $options = [
            'cost' => 12,
        ];

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public static function verify(string $password, string $salt, string $hashPassword): bool {
        return password_verify($password . $salt, $hashPassword);
    }    
}