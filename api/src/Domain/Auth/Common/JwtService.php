<?php

namespace Src\Domain\Auth\Common;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService {
    protected static function getSecretKey(): string {
        return $_ENV['JWT_SECRET'];
    }

    public static function generateToken(array $payload, int $expirationTime = 3600): string {
        $issuedAt = time();
        $payload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $issuedAt + $expirationTime
        ]);

        return JWT::encode($payload, self::getSecretKey(), 'HS256');
    }

    public static function validateToken(string $token): object {
        return JWT::decode($token, new Key(self::getSecretKey(), 'HS256'));
    }
}