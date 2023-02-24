<?php

namespace App\Steam\Request\Dto;

class CurrentTopPlayedGameResponseDto
{
    public function __construct(
        public readonly GameDetailDto $gameDetailDto,
        public readonly int $concurrentInGame,
        public readonly int $peakInGame
    )
    {
    }
}