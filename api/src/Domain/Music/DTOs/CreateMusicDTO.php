<?php

namespace Src\Domain\Music\DTOs;

use Base\BaseDTO;

class CreateMusicDTO extends BaseDTO
{
    public ?string $uuid;
    public ?string $name;
    public ?string $author;
    public ?string $tone;
    public ?string $time_signature;
    public ?string $lyrics = null;
    public ?string $music_score = null;
    public ?string $reference_links = null;
    
    public function isEmpty(): bool
    {
        return empty($this->name) && empty($this->author) && empty($this->tone);
    }    
}