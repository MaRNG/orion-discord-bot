<?php

namespace App\Steam\Request\Dto;

class GameDetailDto
{
    public function __construct(
        public readonly string $gameName,
        public readonly ?string $gameUrl,
        public readonly array $publishers,
        public readonly ?\DateTime $releaseDate
    )
    {
    }
}