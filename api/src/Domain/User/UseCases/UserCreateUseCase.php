<?php
namespace Src\Domain\User\UseCases;

use Base\BaseUseCase;
use Src\Domain\User\DTOs\CreateUserDTO;
use Src\Domain\User\Infra\Common;
use Src\Domain\User\User;

final class UserCreateUseCase extends BaseUseCase
{
    public function __invoke(CreateUserDTO $dto): User
    {
        $user = Common::existsByEmail($dto->email);
        if ($user) throw new \Exception("E-mail já utilizado!", 400);
    
        $user = Common::existsByUsername($dto->username);
        if ($user) throw new \Exception("Username já utilizado!", 400);        
    
        $prep = Common::preparePassword('123');
    
        $dto->salt = $prep['salt'];
        $dto->password_hash = $prep['password_hash'];
        return $this->repository->create($dto);
    }
}