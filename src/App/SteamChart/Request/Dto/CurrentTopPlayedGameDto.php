<?php

namespace App\SteamChart\Request\Dto;

class CurrentTopPlayedGameDto
{
    public function __construct(
        public readonly string $gameId,
        public readonly string $gameTitle,
        public readonly int $playerCount,
    )
    {
    }
}