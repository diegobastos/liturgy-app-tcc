<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
use Illuminate\Pagination\LengthAwarePaginator;

final class ScaleFindAllUseCase extends BaseUseCase
{
    public function __invoke(int $page = 1, int $limit = 10): LengthAwarePaginator
    {
        return $this->repository->findAllPaged($page, $limit);
    }
}