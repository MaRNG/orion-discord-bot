<?php

namespace App\Bot\Embed\Generator;

use App\SteamChart\Request\Dto\AllTimeTopPlayedGameDto;
use App\SteamChart\Request\Dto\AllTimeTopPlayedGamesDto;
use App\Util\NumberFormatter;
use Discord\Parts\Embed\Embed;

class SteamAllTimePlayerCountEmbedGenerator
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
     * @param AllTimeTopPlayedGamesDto $allTimeTopPlayedGamesDto
     * @return array<string, string>
     */
    public static function generate(AllTimeTopPlayedGamesDto $allTimeTopPlayedGamesDto): array
    {
        return [
            'type' => Embed::TYPE_RICH,
            'title' => "Most played games all time on Steam",
            'description' => implode(PHP_EOL, self::generateDescriptionRows($allTimeTopPlayedGamesDto->games)),
            'colors' => '0x391368'
        ];
    }

    /**
     * @param array<AllTimeTopPlayedGameDto> $games
     * @return array<string>
     */
    private static function generateDescriptionRows(array $games): array
    {
        $rows = [];

        $index = 1;

        foreach ($games as $game) {
            $playerCount = NumberFormatter::format($game->playerCount);
            $month = $game->peakMonth->format('F Y');
            $rows[] = (self::NUMBER_ICONS[$index] ?? (string)$index) . " - **{$game->gameTitle}** - `{$playerCount} players` - on {$month}" ;

            $index++;
        }

        return $rows;
    }
}