<?php

namespace App\Bot\Embed\Generator;

use App\Steam\Request\Dto\CurrentTopPlayedGameResponseDto;
use App\Steam\Request\Dto\CurrentTopPlayedGamesResponseDto;
use App\Util\NumberFormatter;
use Discord\Parts\Embed\Embed;

class SteamCurrentPlayerCountEmbedGenerator
{
    public const NUMBER_ICONS = [
        1 => ':one:',
        2 => ':two:',
        3 => ':three:',
        4 => ':four:',
        5 => ':five:',
        6 => ':six:',
        7 => ':seven:',
        8 => ':eight:',
        9 => ':nine:',
        10 => ':keycap_ten:',
    ];

    /**
     * @param CurrentTopPlayedGamesResponseDto $currentTopPlayedGamesDto
     * @return array<string, string>
     */
    public static function generate(CurrentTopPlayedGamesResponseDto $currentTopPlayedGamesDto): array
    {
        return [
            'type' => Embed::TYPE_RICH,
            'title' => "Most currently played games on Steam",
            'description' => implode(PHP_EOL, self::generateDescriptionRows($currentTopPlayedGamesDto->games)),
            'colors' => '0x391368'
        ];
    }

    /**
     * @param array<CurrentTopPlayedGameResponseDto> $games
     * @return array<string>
     */
    private static function generateDescriptionRows(array $games): array
    {
        $rows = [];

        $index = 1;

        foreach ($games as $game) {
            $playerCount = NumberFormatter::format($game->concurrentInGame);
            $rows[] = (self::NUMBER_ICONS[$index] ?? (string)$index) . " - **{$game->gameDetailDto->gameName}** - `{$playerCount} players`" ;

            $index++;
        }

        return $rows;
    }
}