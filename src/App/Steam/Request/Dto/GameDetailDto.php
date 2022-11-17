<?php

namespace App\Steam\Request\Dto;

class GameDetailDto
{
    /**
     * @param string $gameName
     * @param string|null $gameUrl
     * @param array<string> $publishers
     * @param \DateTime|null $releaseDate
     */
    public function __construct(
        public readonly string $gameName,
        public readonly ?string $gameUrl,
        public readonly array $publishers,
        public readonly ?\DateTime $releaseDate
    )
    {
    }
}