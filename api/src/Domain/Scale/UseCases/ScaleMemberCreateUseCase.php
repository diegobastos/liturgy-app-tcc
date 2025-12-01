<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
use Src\Domain\Scale\DTOs\MemberDTO;
use Src\Domain\Scale\ScaleMember;
use Src\Domain\Scale\ScaleMemberRepository;

final class ScaleMemberCreateUseCase extends BaseUseCase
{
    public function __invoke(MemberDTO $dto): ScaleMember
    {      
        $dto->status = 'CONFIRMED';
        return $this->repository->create($dto);
    }
}