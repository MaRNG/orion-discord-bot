<?php

namespace App\Steam;

class SteamResponse
{
    /**
     * @param int $statusCode
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly int   $statusCode,
        private readonly array $data
    )
    {
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}