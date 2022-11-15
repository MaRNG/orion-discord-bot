<?php

namespace App\Steam\Request\Mapper;

use App\Steam\Request\Dto\GameDetailDto;

class GameDetailResultMapper
{
    public static function mapToGameDetailDto(array $result): GameDetailDto
    {
        return new GameDetailDto($result['data']['website'], $result['data']['publishers'], self::mapReleaseDateTime($result['data']['release_date']['date'] ?? null));
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