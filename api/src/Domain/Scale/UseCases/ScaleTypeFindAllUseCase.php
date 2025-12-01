<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
final class ScaleTypeFindAllUseCase extends BaseUseCase
{
    public function __invoke(): mixed
    {
        return $this->repository->findAll();
    }
}