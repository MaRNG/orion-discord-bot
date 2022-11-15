<?php

namespace App\Config\Storage;

class Credentials
{
    public function __construct(private string $token, private string $steamApiKey)
    {
    }

    /**
     * @return string
     */
    public function getSteamApiKey(): string
    {
        return $this->steamApiKey;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}