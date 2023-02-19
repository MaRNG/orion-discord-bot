<?php

namespace App\Bot\Embed\Generator;

use App\SteamChart\Request\Dto\CurrentTopPlayedGameDto;
use App\SteamChart\Request\Dto\CurrentTopPlayedGamesDto;
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
     * @param CurrentTopPlayedGamesDto $currentTopPlayedGamesDto
     * @return array<string, string>
     */
    public static function generate(CurrentTopPlayedGamesDto $currentTopPlayedGamesDto): array
    {
        return [
            'type' => Embed::TYPE_RICH,
            'title' => "Most currently played games on Steam",
            'description' => implode(PHP_EOL, self::generateDescriptionRows($currentTopPlayedGamesDto->games)),
            'colors' => '0x391368'
        ];
    }

    /**
     * @param array<CurrentTopPlayedGameDto> $games
     * @return array<string>
     */
    private static function generateDescriptionRows(array $games): array
    {
        $rows = [];

        $index = 1;

        foreach ($games as $game) {
            $playerCount = NumberFormatter::format($game->playerCount);
            $rows[] = (self::NUMBER_ICONS[$index] ?? (string)$index) . " - **{$game->gameTitle}** - `{$playerCount} players`" ;

            $index++;
        }

        return $rows;
    }
}