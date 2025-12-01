<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
use Src\Domain\Scale\DTOs\CreateScaleDTO;
use Src\Domain\Scale\DTOs\MemberDTO;
use Src\Domain\Scale\Scale;
use Throwable;

final class ScaleCreateUseCase extends BaseUseCase
{
    public function __invoke(CreateScaleDTO $dto): Scale
    {
        $members = $dto->members ?? [];
        $scale = $this->repository->create($dto);
        foreach ($members as $m) { 
            $member = new MemberDTO($m);
            $member->scale_id = $scale->id;
            ScaleMemberCreateUseCase::getInstance()($member);
        }
        return $scale;
    }
}