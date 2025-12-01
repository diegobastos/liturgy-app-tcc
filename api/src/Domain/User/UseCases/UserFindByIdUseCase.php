<?php
namespace Src\Domain\User\UseCases;

use Base\BaseUseCase;
use Src\Domain\User\User;

final class UserFindByIdUseCase extends BaseUseCase
{
    public function __invoke(int $id): User
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $item;
    }
}