<?php

namespace App\Bot\Embed\Generator;

use Discord\Parts\Embed\Embed;

class AboutBotEmbedGenerator
{
    /**
     * @return array<string, string|array<mixed>>
     */
    public static function generate(): array
    {
        $embed = [
            'type' => Embed::TYPE_RICH,
            'title' => 'About Orion',
            'description' => "Bot is mainly use to show current player count for any Steam game. :desktop:\n:keyboard: Bot is created by [MaRNG](https://github.com/MaRNG) :technologist:",
            'colors' => '0x391368',
            'fields' => [
                [
                    'name' => ':keyboard: Written in ',
                    'value' => 'PHP',
                    'inline' => true,
                ],
                [
                    'name' => ':book: Using library',
                    'value' => '[DiscordPHP](https://github.com/discord-php/DiscordPHP)',
                    'inline' => true,
                ],
                [
                    'name' => ':pencil: Source',
                    'value' => '[GitHub](https://github.com/MaRNG/discord-bot-steam)',
                    'inline' => true,
                ],
                [
                    'name' => ':clipboard: License',
                    'value' => 'GNU GPLv3',
                    'inline' => true,
                ],
            ]
        ];

        return $embed;
    }
}