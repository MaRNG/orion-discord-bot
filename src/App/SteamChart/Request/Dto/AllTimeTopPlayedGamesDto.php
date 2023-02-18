<?php

namespace App\SteamChart\Request\Dto;

class AllTimeTopPlayedGamesDto
{
    /**
     * @param array<AllTimeTopPlayedGameDto> $games
     */
    public function __construct(
        public readonly array $games
    )
    {
    }
}