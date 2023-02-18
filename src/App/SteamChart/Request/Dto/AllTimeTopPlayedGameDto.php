<?php

namespace App\SteamChart\Request\Dto;

class AllTimeTopPlayedGameDto
{
    public function __construct(
        public readonly string $gameId,
        public readonly string $gameTitle,
        public readonly int $playerCount,
        public readonly \DateTimeInterface $peakMonth,
    )
    {
    }
}