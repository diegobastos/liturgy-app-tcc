<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
use Src\Domain\Scale\DTOs\MemberDTO;
use Src\Domain\Scale\DTOs\UpdateScaleDTO;
use Src\Domain\Scale\Scale;

final class ScaleMemberUpdateUseCase extends BaseUseCase
{
    public function __invoke(int $id, MemberDTO $dto): Scale
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        return $this->repository->update($id, $dto);
    }
}