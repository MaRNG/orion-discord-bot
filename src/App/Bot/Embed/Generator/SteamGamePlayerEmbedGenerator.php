<?php

namespace App\Bot\Embed\Generator;

use App\Steam\Request\Dto\GamePlayerCountDto;
use App\Steam\Request\Dto\UsersCountDto;
use App\Util\NumberFormatter;
use Discord\Parts\Embed\Embed;

class SteamGamePlayerEmbedGenerator
{
    public static function generate(UsersCountDto $usersCountDto): array
    {
        $embed = [
            'type' => Embed::TYPE_RICH,
            'title' => 'Steam player count',
            'description' => '',
            'url' => 'https://store.steampowered.com/',
            'colors' => '0x391368',
            'fields' => [
                [
                    'name' => 'Online players :slight_smile:',
                    'value' => NumberFormatter::format($usersCountDto->usersOnline),
                    'inline' => true,
                ],
                [
                    'name' => 'In-game players :video_game:',
                    'value' => NumberFormatter::format($usersCountDto->usersIngame),
                    'inline' => true,
                ],
            ]
        ];

        return $embed;
    }
}