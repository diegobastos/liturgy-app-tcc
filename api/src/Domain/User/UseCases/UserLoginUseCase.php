<?php

namespace Src\Domain\User\UseCases;

use Base\BaseUseCase;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;

final class UserLoginUseCase extends BaseUseCase
{
    public function __invoke(string $emailUsername, string $password): ?User
    {
        $this->repository = new UserRepository();
        $user = User::where('email', $emailUsername)
            ->orWhere('username', $emailUsername)
            ->first();

        if (!$user) {
            return null;
        }

        $inputHash = hash('sha256', $password . $user->salt);

        if (!hash_equals($user->password_hash, $inputHash)) {
            return null;
        }

        return $user;
    }    
}