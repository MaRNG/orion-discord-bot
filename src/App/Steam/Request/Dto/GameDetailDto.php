<?php

namespace App\Steam\Request\Dto;

class GameDetailDto
{
    public function __construct(
        private string $gameUrl,
        private array $publishers,
        private ?\DateTime $releaseDate
    )
    {
    }

    /**
     * @return \DateTime|null
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @return array
     */
    public function getPublishers()
    {
        return $this->publishers;
    }

    /**
     * @return string
     */
    public function getGameUrl()
    {
        return $this->gameUrl;
    }
}