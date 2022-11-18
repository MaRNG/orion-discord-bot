<?php

namespace App\Steam\Request\Mapper;

use App\Exception\SteamRequestException;
use App\Steam\Request\Dto\GameReviewsDto;

class GameReviewsResultMapper
{
    public static function mapToGameReviews(array $steamResults): GameReviewsDto
    {
        if (isset($steamResults['success'], $steamResults['query_summary']) && ((int)$steamResults['success']) === 1)
        {
            return new GameReviewsDto(
                (int)$steamResults['query_summary']['total_positive'],
                (int)$steamResults['query_summary']['total_negative'],
                (int)$steamResults['query_summary']['total_reviews']
            );
        }

        throw new SteamRequestException('Game couldn\'t be found on Steam!');
    }
}