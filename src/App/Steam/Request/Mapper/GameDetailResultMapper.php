<?php

namespace App\Steam\Request\Mapper;

use App\Exception\SteamRequestException;
use App\Steam\Request\Dto\GameDetailDto;

class GameDetailResultMapper
{
    /**
     * @param array<mixed> $result
     * @return GameDetailDto
     * @throws SteamRequestException
     */
    public static function mapToGameDetailDto(array $result): GameDetailDto
    {
        if (isset($result['data']))
        {
            return new GameDetailDto($result['data']['name'], $result['data']['website'], $result['data']['publishers'], self::mapReleaseDateTime($result['data']['release_date']['date'] ?? null));
        }

        throw new SteamRequestException('Game couldn\'t be found on Steam!');
    }

    private static function mapReleaseDateTime(?string $originDateTime): ?\DateTime
    {
        if ($originDateTime)
        {
            $dateTime = \DateTime::createFromFormat('d M, Y', $originDateTime);

            return $dateTime ?: null;
        }

        return null;
    }
}