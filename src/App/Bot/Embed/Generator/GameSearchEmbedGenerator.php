<?php

namespace App\Bot\Embed\Generator;

use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\GameSearchDto;
use Discord\Parts\Embed\Embed;

class GameSearchEmbedGenerator
{
    public const NUMBER_ICONS = [
        1 => ':one:',
        2 => ':two:',
        3 => ':three:',
        4 => ':four:',
        5 => ':five:',
    ];

    /**
     * @param GameSearchCollectionDto $gameSearchCollectionDto
     * @return array<string, string>
     */
    public static function generate(GameSearchCollectionDto $gameSearchCollectionDto): array
    {
        return [
            'type' => Embed::TYPE_RICH,
            'title' => "Searching for \"{$gameSearchCollectionDto->searchQuery}\"",
            'description' => 'Found multiple games, please select one!' . PHP_EOL . implode(PHP_EOL, self::generateDescriptionRows($gameSearchCollectionDto->gameSearchList)),
            'colors' => '0x70e814'
        ];
    }

    /**
     * @param array<GameSearchDto> $gameSearchDtoItems
     * @return array<string>
     */
    private static function generateDescriptionRows(array $gameSearchDtoItems): array
    {
        $rows = [];

        $index = 1;

        foreach ($gameSearchDtoItems as $gameSearchDtoItem) {
            $rows[] = (self::NUMBER_ICONS[$index] ?? (string)$index) . " - **{$gameSearchDtoItem->getName()}**" ;

            $index++;
        }

        return $rows;
    }
}