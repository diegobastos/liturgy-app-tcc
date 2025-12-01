<?php
namespace Src\Domain\Music\UseCases;

use Base\BaseUseCase;
use Exception;
use Src\Domain\Music\DTOs\CreateMusicDTO;
use Src\Domain\Music\Music;

final class MusicCreateUseCase extends BaseUseCase
{
    public function __invoke(CreateMusicDTO $dto): Music
    {
        //try {
            return $this->repository->create($dto);
        //} catch (Exception $e) {
        //     print_r($dto);
        //    print_r($e->getMessage());
        //    print_r($e->getCode());
        //    die();
        //}
    }
}