<?php

namespace App\Model\Statistic\Storage;

class StatisticOption
{
    public function __construct(
        public readonly string $name,
        public readonly string $value
    )
    {
    }
}