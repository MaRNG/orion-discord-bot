<?php

namespace App\Steam\Request\Dto;

class GameSearchDto
{
    public function __construct(
        private string $steamId,
        private string $name,
        private string $iconUrl,
        private string $logo
    )
    {
    }

    /**
     * @return string
     */
    public function getSteamId()
    {
        return $this->steamId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
}