<?php

namespace Base;

use Spatie\DataTransferObject\DataTransferObject;

abstract class BaseDTO extends DataTransferObject
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public abstract function isEmpty(): bool;
}