<?php

namespace App\Model\Statistic\Storage;

class StatisticUser
{
    public function __construct(
        public readonly  string $id,
        public readonly string $name
    )
    {
    }
}