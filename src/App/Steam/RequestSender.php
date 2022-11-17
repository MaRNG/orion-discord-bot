<?php

namespace App\Steam;

use App\Config\CredentialsLoader;
use App\Exception\SteamRequestException;
use App\Steam\Request\Dto\GameDetailDto;
use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\GamePlayerCountDto;
use App\Steam\Request\Mapper\GameDetailResultMapper;
use App\Steam\Request\Mapper\GamePlayerCountResultMapper;
use App\Steam\Request\Mapper\GameSearchResultMapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RequestSender
{
    public static function getPlayerCount(string $steamGameId): GamePlayerCountDto
    {
        $steamApiKey = CredentialsLoader::load()->getSteamApiKey();
        $response = self::sendRequest("https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1/?key={$steamApiKey}&appid={$steamGameId}");

        if ($response->getStatusCode() === 200)
        {
            if ($response->getData()['response']['result'] && ((int)$response->getData()['response']['result']) === 1)
            {
                return GamePlayerCountResultMapper::mapToGamePlayerCount($response->getData());
            }
            else
            {
                throw new SteamRequestException("Game with id #{$steamGameId} is not found!");
            }
        }

        throw new SteamRequestException('Steam player count request raised exception!');
    }

    public static function getGameSearch(string $searchQuery): GameSearchCollectionDto
    {
        $urlEncodedSearchQuery = urlencode($searchQuery);
        $response = self::sendRequest("https://steamcommunity.com/actions/SearchApps/{$urlEncodedSearchQuery}");

        if ($response->getStatusCode() === 200)
        {
            return GameSearchResultMapper::mapToGameSearchCollection($response->getData(), $searchQuery);
        }

        throw new SteamRequestException('Steam search raised exception!');
    }

    public static function getGameDetail(string $steamGameId): GameDetailDto
    {
        $response = self::sendRequest("https://store.steampowered.com/api/appdetails?appids={$steamGameId}&l=english");

        if ($response->getStatusCode() === 200 && isset($response->getData()[$steamGameId]))
        {
            if ($response->getData()[$steamGameId]['success'])
            {
                return GameDetailResultMapper::mapToGameDetailDto($response->getData()[$steamGameId]);
            }
            else
            {
                throw new SteamRequestException("Game with id #{$steamGameId} is not found!");
            }
        }

        throw new SteamRequestException('Steam appdetails raised exception!');
    }

    /**
     * @param string $url
     * @param string $method
     * @return SteamResponse
     * @throws GuzzleException
     * @throws SteamRequestException
     * @throws \JsonException
     */
    private static function sendRequest(string $url, string $method = 'GET'): SteamResponse
    {
        $client = new Client();

        $response = $client->request($method, $url);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299)
        {
            return new SteamResponse($response->getStatusCode(), json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR));
        }

        throw new SteamRequestException('Error appeared while requesting Steam API.');
    }
}