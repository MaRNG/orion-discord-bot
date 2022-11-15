<?php

namespace App\Steam;

class SteamResponse
{
    public function __construct(
        private int $statusCode,
        private array $data
    )
    {
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}