<?php

namespace App\SteamChart;

use App\Exception\SteamChartRequestException;
use App\SteamChart\Request\Dto\AllTimeTopPlayedGameDto;
use App\SteamChart\Request\Dto\AllTimeTopPlayedGamesDto;
use App\SteamChart\Request\Dto\CurrentTopPlayedGameDto;
use App\SteamChart\Request\Dto\CurrentTopPlayedGamesDto;
use App\SteamChart\WebScrape\WebScraper;

class RequestSender
{
    /**
     * @return CurrentTopPlayedGamesDto
     * @throws \App\Exception\SteamChartRequestException
     */
    public static function getCurrentTopPlayedGames(): CurrentTopPlayedGamesDto
    {
        $webScrapedCurrentTopPlayedGames = WebScraper::scrapeCurrentTopPlayedGames();

        return new CurrentTopPlayedGamesDto(array_map(static function(array $webScrapedGame) {
            return new CurrentTopPlayedGameDto(
                strtr($webScrapedGame['relativeUrl'], ['/app/' => '']),
                $webScrapedGame['title'],
                (int)$webScrapedGame['playerCount']
            );
        }, $webScrapedCurrentTopPlayedGames));
    }

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