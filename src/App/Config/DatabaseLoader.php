<?php

namespace App\Config;

use App\Config\Storage\DatabaseConfig;
use App\Util\FilePathHelper;

class DatabaseLoader
{
    public const DATABASE_CONFIG_FILE_PATH = __DIR__ . '/../../../config/database.json';

    public static function load(): DatabaseConfig
    {
        $rootDirPath = FilePathHelper::getRootDirPath();
        $config = ConfigLoader::load(self::DATABASE_CONFIG_FILE_PATH);

        return new DatabaseConfig("{$rootDirPath}/{$config['filepath']}", $config['entities'], $config['throwsExceptions']);
    }
}