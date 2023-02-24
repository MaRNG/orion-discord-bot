<?php

namespace App\SteamChart;

use App\Exception\SteamChartRequestException;
use App\SteamChart\Request\Dto\AllTimeTopPlayedGameDto;
use App\SteamChart\Request\Dto\AllTimeTopPlayedGamesDto;
use App\SteamChart\WebScrape\WebScraper;

class RequestSender
{
    /**
     * @return AllTimeTopPlayedGamesDto
     * @throws SteamChartRequestException
     */
    public static function getAllTimeTopPlayedGames(): AllTimeTopPlayedGamesDto
    {
        $scrapeAllTimeTopPlayedGames = WebScraper::scrapeAllTimeTopPlayedGames();

        return new AllTimeTopPlayedGamesDto(array_map(static function(array $webScrapedGame) {
            return new AllTimeTopPlayedGameDto(
                strtr($webScrapedGame['relativeUrl'], ['/app/' => '']),
                $webScrapedGame['title'],
                (int)$webScrapedGame['playerCount'],
                $webScrapedGame['peakMonth']
            );
        }, $scrapeAllTimeTopPlayedGames));
    }
}