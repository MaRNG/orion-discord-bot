<?php

namespace App\Config;

class ConfigLoader
{
    /**
     * @param string $pathToConfigFile
     * @return array<mixed>
     * @throws \JsonException
     */
    public static function load(string $pathToConfigFile): array
    {
        if (file_exists($pathToConfigFile))
        {
            $configFileContent = file_get_contents($pathToConfigFile);

            if ($configFileContent)
            {
                $parsedJson = json_decode($configFileContent, true, 512, JSON_THROW_ON_ERROR);

                if ($parsedJson)
                {
                    return $parsedJson;
                }
            }

            throw new \InvalidArgumentException("Passed JSON file is not valid.");
        }

        throw new \InvalidArgumentException("Config file {$pathToConfigFile} is not exists.");
    }
}