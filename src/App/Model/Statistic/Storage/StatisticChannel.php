<?php

namespace App\Model\Statistic\Storage;

class StatisticChannel
{
    public function __construct(
        public readonly  string $id,
        public readonly string $name
    )
    {
    }
}