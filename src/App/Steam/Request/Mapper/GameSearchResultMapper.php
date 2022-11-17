<?php

namespace App\Steam\Request\Mapper;

use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\GameSearchDto;

class GameSearchResultMapper
{
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