<?php

namespace App\Steam\Request\Mapper;

use App\Steam\Request\Dto\UsersCountDto;

class UsersCountResultMapper
{
    /**
     * @param array{users_online: string, users_ingame: string} $responseData
     * @return UsersCountDto
     */
    public static function mapToUsersCountDto(array $responseData): UsersCountDto
    {
        $usersOnline = trim(strtr($responseData['users_online'], [',' => '']));
        $usersIngame = trim(strtr($responseData['users_ingame'], [',' => '']));

        return new UsersCountDto((int)$usersOnline, (int)$usersIngame);
    }
}