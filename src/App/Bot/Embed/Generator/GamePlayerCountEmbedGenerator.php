<?php

namespace App\Bot\Embed\Generator;

use App\Steam\Request\Dto\GameDetailDto;
use App\Steam\Request\Dto\GamePlayerCountDto;
use App\Steam\Request\Dto\GameReviewsDto;
use App\Steam\Request\Dto\GameSearchDto;
use App\Util\GameReviewsCalculator;
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

        $embed = [
            'type' => Embed::TYPE_RICH,
            'title' => $gameDetailDto->gameName,
            'description' => '',
            'colors' => '0x391368',
            'fields' => [
                [
                    'name' => 'Playing :video_game:',
                    'value' => "{$gamePlayerCountDto->playerCount} players",
                    'inline' => true,
                ],
                [
                    'name' => 'Reviews ' . GameReviewsCalculator::getEmojiByPositivePercent($positiveReviewsPercentage),
                    'value' => "{$positiveReviewsPercentage}%",
                    'inline' => true,
                ],
                [
                    'name' => 'Release date :calendar:',
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