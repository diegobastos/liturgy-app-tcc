<?php
namespace Src\Domain\User\UseCases;

use Base\BaseUseCase;
use Src\Domain\User\DTOs\UpdateUserDTO;
use Src\Domain\User\User;

final class UserUpdateUseCase extends BaseUseCase
{
    public function __invoke(int $id, UpdateUserDTO $dto): User
    {
        $item = $this->repository->findById($id);

        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $this->repository->update($id, $dto);
    }
}