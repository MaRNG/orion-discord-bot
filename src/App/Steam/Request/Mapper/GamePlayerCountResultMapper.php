<?php

namespace App\Steam\Request\Mapper;

use App\Exception\SteamRequestException;
use App\Steam\Request\Dto\GamePlayerCountDto;

class GamePlayerCountResultMapper
{
    /**
     * @param array<mixed> $steamResults
     * @return GamePlayerCountDto
     * @throws SteamRequestException
     */
    public static function mapToGamePlayerCount(array $steamResults): GamePlayerCountDto
    {
        if (isset($steamResults['response']['player_count']))
        {
            return new GamePlayerCountDto((int)$steamResults['response']['player_count']);
        }

        throw new SteamRequestException('Game couldn\'t be found on Steam!');
    }
}