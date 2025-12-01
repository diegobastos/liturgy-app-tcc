<?php
namespace Src\Domain\User\UseCases;

use Base\BaseUseCase;

final class UserDeleteUseCase extends BaseUseCase
{
    public function __invoke(int $id): bool
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $this->repository->delete($id);
    }
}