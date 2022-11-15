<?php

namespace App\Steam;

use App\Exception\SteamRequestException;
use App\Steam\Request\Dto\GameDetailDto;
use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\PlayerCountDto;
use App\Steam\Request\Mapper\GameDetailResultMapper;
use App\Steam\Request\Mapper\GameSearchResultMapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RequestSender
{
    public function getPlayerCount(string $steamGameId): PlayerCountDto
    {

    }

    public function getGameSearch(string $searchQuery): GameSearchCollectionDto
    {
        $urlEncodedSearchQuery = urlencode($searchQuery);
        $response = $this->sendRequest("https://steamcommunity.com/actions/SearchApps/{$urlEncodedSearchQuery}");

        if ($response->getStatusCode() === 200)
        {
            return GameSearchResultMapper::mapToGameSearchCollection($response->getData());
        }

        throw new SteamRequestException('Steam search raised exception!');
    }

    public function getGameDetail(string $steamGameId): GameDetailDto
    {
        $response = $this->sendRequest("https://store.steampowered.com/api/appdetails?appids={$steamGameId}&l=english");

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
     * @return array
     * @throws GuzzleException
     * @throws \JsonException
     * @throws SteamRequestException
     */
    private function sendRequest(string $url, string $method = 'GET'): SteamResponse
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