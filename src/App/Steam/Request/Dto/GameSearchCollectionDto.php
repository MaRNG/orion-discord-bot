<?php

namespace App\Steam\Request\Dto;

class GameSearchCollectionDto
{
    private int $count;

    /**
     * @param array<GameSearchDto> $gameSearchList
     * @param string $searchQuery
     */
    public function __construct(
        public readonly array $gameSearchList,
        public readonly string $searchQuery
    )
    {
        $this->count = count($this->gameSearchList);
    }

    public function count(): int
    {
        return $this->count;
    }
}