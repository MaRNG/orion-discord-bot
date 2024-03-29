<?php

namespace App\Bot\Embed\Generator;

use App\Steam\Request\Dto\GameDetailDto;
use App\Steam\Request\Dto\GamePlayerCountDto;
use App\Steam\Request\Dto\GameReviewsDto;
use App\Steam\Request\Dto\GameSearchDto;
use App\Util\GameReviewsCalculator;
use App\Util\NumberFormatter;
use Discord\Parts\Embed\Embed;

class GamePlayerCountEmbedGenerator
{
    /**
     * @param GameDetailDto $gameDetailDto
     * @param GamePlayerCountDto $gamePlayerCountDto
     * @param GameSearchDto $gameSearchDto
     * @return array<string, string|array<mixed>>
     */
    public static function generate(GameDetailDto $gameDetailDto, GamePlayerCountDto $gamePlayerCountDto, GameSearchDto $gameSearchDto, GameReviewsDto $gameReviewsDto): array
    {
        $positiveReviewsPercentage = GameReviewsCalculator::calculatePositivePercent($gameReviewsDto->totalReviews, $gameReviewsDto->positiveReviews);

        $reviewsEmoji = GameReviewsCalculator::getEmojiByPositivePercent($positiveReviewsPercentage);
        $playerCount = NumberFormatter::format($gamePlayerCountDto->playerCount);

        $embed = [
            'type' => Embed::TYPE_RICH,
            'title' => $gameDetailDto->gameName,
            'description' => '',
            'colors' => '0x391368',
            'fields' => [
                [
                    'name' => 'Playing :video_game:',
                    'value' => "{$playerCount} players",
                    'inline' => true,
                ],
                [
                    'name' => "Reviews {$reviewsEmoji}",
                    'value' => "[{$positiveReviewsPercentage}%](https://steamcommunity.com/app/{$gameSearchDto->getSteamId()}/reviews/)",
                    'inline' => true,
                ],
                [
                    'name' => 'Release date :calendar:',
                    'value' => $gameDetailDto->releaseDate ? strtr($gameDetailDto->releaseDate?->format('d. m. Y'), [ '.' => '\.' ]) : '',
                    'inline' => true,
                ]
            ],
            'thumbnail' => [
                'url' => $gameSearchDto->getLogo()
            ]
        ];

        if ($gameDetailDto->gameUrl)
        {
            $embed['url'] = "https://store.steampowered.com/app/{$gameSearchDto->getSteamId()}";
        }

        return $embed;
    }
}