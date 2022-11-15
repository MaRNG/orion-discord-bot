<?php

namespace App\Config;

use App\Config\Storage\Credentials;

class CredentialsLoader
{
    public const CREDENTIALS_CONFIG_FILE_PATH = __DIR__ . '/../../Config/creds.json';

    private static ?Credentials $credentials = null;

    public static function load(): Credentials
    {
        if (self::$credentials)
        {
            return self::$credentials;
        }

        if (file_exists(self::CREDENTIALS_CONFIG_FILE_PATH))
        {
            $credentialsConfig = ConfigLoader::load(self::CREDENTIALS_CONFIG_FILE_PATH);

            if (isset($credentialsConfig['token'], $credentialsConfig['steam']['apiKey']))
            {
                return self::$credentials =  new Credentials($credentialsConfig['token'], $credentialsConfig['steam']['apiKey']);
            }

            throw new \InvalidArgumentException('Token is not set in credential config file.');
        }

        if (isset($_ENV['botToken'], $_ENV['steamApiKey']))
        {
            return self::$credentials = new Credentials($_ENV['botToken'], $_ENV['steamApiKey']);
        }

        throw new \InvalidArgumentException("Credentials is not setup.");
    }
}