<?php

namespace App\Steam\Request\Dto;

class GamePlayerCountDto
{
    public function __construct(public readonly int $playerCount)
    {
    }
}