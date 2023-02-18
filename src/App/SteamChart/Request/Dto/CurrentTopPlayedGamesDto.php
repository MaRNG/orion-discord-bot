<?php

namespace App\SteamChart\Request\Dto;

class CurrentTopPlayedGamesDto
{
    /**
     * @param array<CurrentTopPlayedGameDto> $games
     */
    public function __construct(
        public readonly array $games
    )
    {
    }
}