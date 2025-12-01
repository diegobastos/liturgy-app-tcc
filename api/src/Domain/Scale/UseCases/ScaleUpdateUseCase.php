<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
use Src\Domain\Scale\DTOs\MemberDTO;
use Src\Domain\Scale\DTOs\UpdateScaleDTO;
use Src\Domain\Scale\Scale;
use Src\Domain\Scale\ScaleMemberRepository;

final class ScaleUpdateUseCase extends BaseUseCase
{
    public function __invoke(int $id, UpdateScaleDTO $dto): Scale
    {
        $item = $this->repository->findById($id);
        if (!$item) {
            throw new \Exception('Item não encontrado!', 404);
        }

        $dto->uuid = $item->uuid;
        $item = $this->repository->update($id, $dto);
        $members = $dto->members ?? [];
        
        try {
            ScaleMemberDeleteAllUseCase::getInstance(new ScaleMemberRepository())($item->id);
        } catch (\Exception $e) {}
        foreach ($members as $m) { 
            $member = new MemberDTO($m);
            $member->scale_id = $item->id;
            ScaleMemberCreateUseCase::getInstance()($member);
        }        

        return $item->refresh();
    }
}