<?php

namespace App\Steam\Request\Mapper;

use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\GameSearchDto;

class GameSearchResultMapper
{
    /**
     * @param array<array<string, string>> $steamResults
     * @param string $searchQuery
     * @return GameSearchCollectionDto
     */
    public static function mapToGameSearchCollection(array $steamResults, string $searchQuery): GameSearchCollectionDto
    {
        $gameSearchItems = [];

        foreach ($steamResults as $steamResult) {
            $gameSearchItems[] = new GameSearchDto(
                $steamResult['appid'],
                $steamResult['name'],
                $steamResult['icon'],
                $steamResult['logo']
            );
        }

        return new GameSearchCollectionDto($gameSearchItems, $searchQuery);
    }
}