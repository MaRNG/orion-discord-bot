<?php

namespace App\Steam;

use App\Config\CredentialsLoader;
use App\Exception\SteamRequestException;
use App\Steam\Request\Dto\CurrentTopPlayedGamesResponseDto;
use App\Steam\Request\Dto\GameDetailDto;
use App\Steam\Request\Dto\GameReviewsDto;
use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\GamePlayerCountDto;
use App\Steam\Request\Dto\UsersCountDto;
use App\Steam\Request\Mapper\CurrentTopPlayedGamesResultMapper;
use App\Steam\Request\Mapper\GameDetailResultMapper;
use App\Steam\Request\Mapper\GamePlayerCountResultMapper;
use App\Steam\Request\Mapper\GameReviewsResultMapper;
use App\Steam\Request\Mapper\GameSearchResultMapper;
use App\Steam\Request\Mapper\UsersCountResultMapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RequestSender
{
    /**
     * @param string $steamGameId
     * @return GamePlayerCountDto
     * @throws GuzzleException
     * @throws SteamRequestException
     * @throws \JsonException
     */
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
                throw new SteamRequestException("Game's player count with id #{$steamGameId} is not found!");
            }
        }

        throw new SteamRequestException('Steam player count request raised exception!');
    }

    /**
     * @param string $searchQuery
     * @return GameSearchCollectionDto
     * @throws GuzzleException
     * @throws SteamRequestException
     * @throws \JsonException
     */
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

    /**
     * @param string $steamGameId
     * @return GameDetailDto
     * @throws GuzzleException
     * @throws SteamRequestException
     * @throws \JsonException
     */
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
                throw new SteamRequestException("Game's detail with id #{$steamGameId} is not found!");
            }
        }

        throw new SteamRequestException('Steam appdetails raised exception!');
    }

    /**
     * @param string $steamGameId
     * @return GameReviewsDto
     * @throws GuzzleException
     * @throws SteamRequestException
     * @throws \JsonException
     */
    public static function getGameReviews(string $steamGameId): GameReviewsDto
    {
        $response = self::sendRequest("https://store.steampowered.com/appreviews/{$steamGameId}?json=1&purchase_type=all&language=all&num_per_page=0");

        if ($response->getStatusCode() === 200)
        {
            if ($response->getData()['success'])
            {
                return GameReviewsResultMapper::mapToGameReviews($response->getData());
            }
            else
            {
                throw new SteamRequestException("Game's reviews with id #{$steamGameId} is not found!");
            }
        }

        throw new SteamRequestException('Steam game reviews raised exception!');
    }

    /**
     * @return UsersCountDto
     * @throws GuzzleException
     * @throws SteamRequestException
     * @throws \JsonException
     */
    public static function getUsersCount(): UsersCountDto
    {
        $response = self::sendRequest("https://www.valvesoftware.com/cs/about/stats");

        if ($response->getStatusCode() === 200)
        {
            $responseData = $response->getData();

            if (isset($responseData['users_online'], $responseData['users_ingame']))
            {
                return UsersCountResultMapper::mapToUsersCountDto($responseData);
            }
            else
            {
                throw new SteamRequestException('Users count data not found!');
            }
        }

        throw new SteamRequestException('Users count request raised wild exception!');
    }

    /**
     * @return CurrentTopPlayedGamesResponseDto
     * @throws GuzzleException
     * @throws SteamRequestException
     * @throws \JsonException
     */
    public static function getCurrentTopPlayedGames(): CurrentTopPlayedGamesResponseDto
    {
        $response = self::sendRequest("https://api.steampowered.com/ISteamChartsService/GetGamesByConcurrentPlayers/v1");

        if ($response->getStatusCode() === 200)
        {
            $responseData = $response->getData();

            if (isset($responseData['response']['ranks']))
            {
                return CurrentTopPlayedGamesResultMapper::map($responseData);
            }
            else
            {
                throw new SteamRequestException('Response doesn\'t have ranks data!');
            }
        }

        throw new SteamRequestException('Current top played games request raised wild exception!');
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
