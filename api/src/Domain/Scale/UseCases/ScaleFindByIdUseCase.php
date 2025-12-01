<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
use Src\Domain\Scale\Scale;

final class ScaleFindByIdUseCase extends BaseUseCase
{
    public function __invoke(int $id): Scale
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $item;
    }
}