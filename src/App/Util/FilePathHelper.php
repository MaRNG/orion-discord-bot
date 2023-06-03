<?php

namespace App\Util;

class FilePathHelper
{
    private static ?string $rootDirPath = null;

    public static function getRootDirPath(): string
    {
        if (self::$rootDirPath === null)
        {
            self::$rootDirPath = dirname(__DIR__, 3) . '/';
        }

        return self::$rootDirPath;
    }
}