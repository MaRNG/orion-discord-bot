<?php

namespace App\Steam\Request\Mapper;

use App\Steam\Request\Dto\CurrentTopPlayedGameResponseDto;
use App\Steam\Request\Dto\CurrentTopPlayedGamesResponseDto;
use App\Steam\RequestSender;

class CurrentTopPlayedGamesResultMapper
{
    /**
     * @param array $responseData
     * @return CurrentTopPlayedGamesResponseDto
     */
    public static function map(array $responseData): CurrentTopPlayedGamesResponseDto
    {
        $ranks = array_slice($responseData['response']['ranks'], 0, 10);

        return new CurrentTopPlayedGamesResponseDto(array_map(self::mapCurrentTopPlayedGame(...), $ranks));
    }

    /**
     * @param array $rank
     * @return CurrentTopPlayedGameResponseDto
     * @throws \App\Exception\SteamRequestException
     */
    private static function mapCurrentTopPlayedGame(array $rank): CurrentTopPlayedGameResponseDto
    {
        $gameDetailDto = RequestSender::getGameDetail($rank['appid']);

        return new CurrentTopPlayedGameResponseDto($gameDetailDto, $rank['concurrent_in_game'], $rank['peak_in_game']);
    }
}