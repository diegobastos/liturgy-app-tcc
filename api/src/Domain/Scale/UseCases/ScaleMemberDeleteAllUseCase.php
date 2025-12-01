<?php
namespace Src\Domain\Scale\UseCases;

use Base\BaseUseCase;
use Src\Domain\Scale\ScaleMember;

final class ScaleMemberDeleteAllUseCase extends BaseUseCase
{
    public function __invoke(int $scaleId): bool
    {
        return ScaleMember::where('scale_id', $scaleId)->delete();
    }
}