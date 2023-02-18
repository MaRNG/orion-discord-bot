<?php

namespace App\SteamChart\WebScrape;

use App\Exception\SteamChartRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class WebScraper
{
    public const STEAM_CHART_URL = 'https://steamcharts.com/';

    /**
     * @return string
     * @throws SteamChartRequestException
     */
    public static function scrapeHtml(): string
    {
        $httpClient = new Client();

        try {
            $response = $httpClient->get(self::STEAM_CHART_URL);
        } catch (GuzzleException $e) {
            throw new SteamChartRequestException('Fetching data from Steamcharts failed!');
        }

        if ($response->getStatusCode() === 200)
        {
            return $response->getBody()->getContents();
        }

        throw new SteamChartRequestException('Fetching data from Steamcharts failed!');
    }

    /**
     * @return array<array{ title: string, relativeUrl: string, playerCount: int }>
     * @throws SteamChartRequestException
     */
    public static function scrapeCurrentTopPlayedGames(): array
    {
        $html = self::scrapeHtml();

        $games = [];

        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        $xpath = new \DOMXPath($doc);

        /** @var \DOMElement $gameTitleRowElement */
        foreach ($xpath->evaluate('//*[@id="top-games"]/tbody/tr/td[contains(@class, "game-name")]/a') as $idx => $gameTitleRowElement) {
            $games[$idx] = [ 'title' => trim($gameTitleRowElement->textContent), 'relativeUrl' => $gameTitleRowElement->getAttribute('href') ];
        }

        /** @var \DOMElement $gamePlayerCountRowElement */
        foreach ($xpath->evaluate('//*[@id="top-games"]/tbody/tr/td[3]') as $idx => $gamePlayerCountRowElement) {
            $games[$idx]['playerCount'] = (int)trim(strtr($gamePlayerCountRowElement->textContent, [',' => '']));
        }

        return $games;
    }

    /**
     * @return array
     * @throws SteamChartRequestException
     */
    public static function scrapeAllTimeTopPlayedGames(): array
    {
        $html = self::scrapeHtml();

        $games = [];

        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        $xpath = new \DOMXPath($doc);

        /** @var \DOMElement $gameTitleRowElement */
        foreach ($xpath->evaluate('//*[@id="toppeaks"]/tbody/tr/td[1]/a') as $idx => $gameTitleRowElement) {
            $games[$idx] = [ 'title' => trim($gameTitleRowElement->textContent), 'relativeUrl' => $gameTitleRowElement->getAttribute('href') ];
        }

        /** @var \DOMElement $gamePlayerCountRowElement */
        foreach ($xpath->evaluate('//*[@id="toppeaks"]/tbody/tr/td[2]') as $idx => $gamePlayerCountRowElement) {
            $games[$idx]['playerCount'] = (int)trim(strtr($gamePlayerCountRowElement->textContent, [',' => '']));
        }

        /** @var \DOMElement $peakMonthRowElement */
        foreach ($xpath->evaluate('//*[@id="toppeaks"]/tbody/tr/td[3]') as $idx => $peakMonthRowElement) {
            $games[$idx]['peakMonth'] = \DateTime::createFromFormat(\DateTimeInterface::ATOM, trim($peakMonthRowElement->textContent));
        }

        return $games;
    }
}