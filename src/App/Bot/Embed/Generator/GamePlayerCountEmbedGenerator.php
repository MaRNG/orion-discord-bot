<?php

namespace App\Bot\Embed\Generator;

use App\Steam\Request\Dto\GameDetailDto;
use App\Steam\Request\Dto\GamePlayerCountDto;
use App\Steam\Request\Dto\GameSearchDto;
use Discord\Parts\Embed\Embed;

class GamePlayerCountEmbedGenerator
{
    /**
     * @param GameDetailDto $gameDetailDto
     * @param GamePlayerCountDto $gamePlayerCountDto
     * @param GameSearchDto $gameSearchDto
     * @return array<string, string|array<mixed>>
     */
    public static function generate(GameDetailDto $gameDetailDto, GamePlayerCountDto $gamePlayerCountDto, GameSearchDto $gameSearchDto): array
    {
        $embed = [
            'type' => Embed::TYPE_RICH,
            'title' => $gameDetailDto->gameName,
            'description' => '',
            'colors' => '0x70e814',
            'fields' => [
                [
                    'name' => 'Playing',
                    'value' => "{$gamePlayerCountDto->playerCount} players",
                    'inline' => true,
                ],
                [
                    'name' => 'Release date',
                    'value' => $gameDetailDto->releaseDate?->format('d. m. Y'),
                    'inline' => true,
                ]
            ],
            'thumbnail' => [
                'url' => $gameSearchDto->getLogo()
            ]
        ];

        if ($gameDetailDto->gameUrl)
        {
            $embed['url'] = $gameDetailDto->gameUrl;
        }

        return $embed;
    }
}