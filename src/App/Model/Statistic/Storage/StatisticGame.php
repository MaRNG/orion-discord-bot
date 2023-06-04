<?php

namespace App\Model\Statistic\Storage;

class StatisticGame
{
    public function __construct(
        public readonly string $gameName,
        public readonly int $playerCount,
        public readonly int $reviewsPositive,
    )
    {
    }
}