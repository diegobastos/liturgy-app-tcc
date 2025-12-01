<?php
namespace Src\Domain\Event\UseCases;

use Base\BaseUseCase;
use Illuminate\Pagination\LengthAwarePaginator;

final class EventFindAllUseCase extends BaseUseCase
{
    public function __invoke(int $page = 1, int $limit = 10): LengthAwarePaginator
    {
        return $this->repository->findAllPaged($page, $limit);
    }
}