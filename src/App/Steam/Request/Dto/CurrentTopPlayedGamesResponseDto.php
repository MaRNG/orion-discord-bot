<?php

namespace App\Steam\Request\Dto;

class CurrentTopPlayedGamesResponseDto
{
    /**
     * @param array<CurrentTopPlayedGameResponseDto> $games
     */
    public function __construct(
        public readonly array $games
    )
    {
    }
}