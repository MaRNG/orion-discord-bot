<?php

namespace App\Steam\Request\Dto;

class UsersCountDto
{
    public function __construct(
        public readonly int $usersOnline,
        public readonly int $usersIngame
    )
    {
    }
}