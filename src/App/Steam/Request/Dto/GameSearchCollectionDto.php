<?php

namespace App\Steam\Request\Dto;

class GameSearchCollectionDto
{
    /**
     * @param array<GameSearchDto> $gameSearchList
     */
    public function __construct(
        private array $gameSearchList
    )
    {
    }

    /**
     * @return array<GameSearchDto>
     */
    public function getGameSearchList(): array
    {
        return $this->gameSearchList;
    }
}