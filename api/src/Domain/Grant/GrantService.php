<?php
namespace Src\Domain\Grant;

use Base\BaseService;

final class GrantService extends BaseService
{
    public function __construct(GrantRepository $repository)
    {
        parent::__construct($repository);
    }

    //specific methods here
}
