<?php

namespace App\Model\Statistic;

use App\Bot\Listener\UnitMessageListener;
use App\Model\Database\Entity\LogStatistic;
use App\Model\Database\Handler\EntityHandler;
use App\Model\Statistic\Storage\StatisticChannel;
use App\Model\Statistic\Storage\StatisticGame;
use App\Model\Statistic\Storage\StatisticOption;
use App\Model\Statistic\Storage\StatisticUser;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;
use Discord\Parts\Interactions\Request\Option;

class StatisticLogger
{
    public const STATUS_OK = 200;
    public const STATUS_BAD_INPUT = 400;
    public const STATUS_ERROR = 500;
    public const STATUS_NOT_FOUND = 404;

    public static function log(
        ?StatisticUser $statisticUser,
        ?StatisticChannel $statisticChannel,
        ?StatisticOption $statisticOption,
        ?StatisticGame $statisticGame,
        ?string $requestMessage,
        string $action,
        bool $foundTooManyGames,
        bool $selectedGame,
        int $responseStatusCode
    ): LogStatistic
    {
        printf('Start logging statistic...' . PHP_EOL);

        $log = new LogStatistic();

        printf('Hydrating statistic data to entity...' . PHP_EOL);

        $log
            ->setUserId($statisticUser?->id)
            ->setUsername($statisticUser?->name)
            ->setChannelId($statisticChannel?->id)
            ->setChannelName($statisticChannel?->name)
            ->setOptionName($statisticOption?->name)
            ->setOptionValue($statisticOption?->value)
            ->setMessage($requestMessage)
            ->setAction($action)
            ->setResponseStatusCode($responseStatusCode)
            ->setFoundGameName($statisticGame?->gameName)
            ->setFoundGamePlayerCount($statisticGame?->playerCount)
            ->setFoundGamePlayerReviewsPositive($statisticGame?->reviewsPositive)
            ->setFoundTooManyGames($foundTooManyGames ? 1 : 0)
            ->setSelectedGame($selectedGame ? 1 : 0)
        ;

        printf('Inserting statistic to database...' . PHP_EOL);
        EntityHandler::getInstance()->insert($log);

        printf('Insert finished!' . PHP_EOL);
        return $log;
    }

    public static function logInteraction(Interaction $interaction, string $action, ?string $foundGameName, ?int $foundGamePlayerCount, ?int $foundGameReviewsPositive, bool $foundTooManyGames, bool $selectedGame, int $responseStatusCode): LogStatistic
    {
        printf('Start logging interaction...' . PHP_EOL);

        $statisticUser = null;
        $statisticChannel = null;
        $statisticOption = null;
        $statisticGame = null;

        $message = null;

        if ($interaction->user !== null)
        {
            printf('Fetching user data...' . PHP_EOL);
            $statisticUser = new StatisticUser($interaction->user->id, $interaction->user->username);
        }

        if ($interaction->channel !== null)
        {
            printf('Fetching channel data...' . PHP_EOL);
            $statisticChannel = new StatisticChannel($interaction->channel->id, $interaction->channel->name);
        }

        if ($interaction->message !== null)
        {
            printf('Fetching request message...' . PHP_EOL);
            $message = $interaction->message->content;
        }

        if ($interaction->data !== null && $interaction->data->options->first() !== null)
        {
            printf('Fetching request option...' . PHP_EOL);
            $option = $interaction->data->options->first();

            if ($option instanceof Option)
            {
                $statisticOption = new StatisticOption($option->name, $option->value ?? '-');
            }
        }

        if ($foundGameName !== null && $foundGamePlayerCount !== null && $foundGameReviewsPositive !== null)
        {
            $statisticGame = new StatisticGame($foundGameName, $foundGamePlayerCount, $foundGameReviewsPositive);
        }

        return self::log($statisticUser, $statisticChannel, $statisticOption, $statisticGame, $message, $action, $foundTooManyGames, $selectedGame, $responseStatusCode);
    }

    public static function logUnitCalculatorMessage(Message $message): LogStatistic
    {
        printf('Start logging unit calculator message...' . PHP_EOL);

        $statisticUser = null;
        $statisticChannel = null;
        $messageContent = null;

        if ($message->author !== null)
        {
            printf('Fetching user data...' . PHP_EOL);
            $statisticUser = new StatisticUser($message->author->id, $message->author->username);
        }

        if ($message->channel !== null)
        {
            printf('Fetching channel data...' . PHP_EOL);
            $statisticChannel = new StatisticChannel($message->channel->id, $message->channel->name);
        }

        if ($message->content !== null)
        {
            printf('Fetching request message...' . PHP_EOL);
            $messageContent = $message->content;
        }

        return self::log(
            $statisticUser,
            $statisticChannel,
            null,
            null,
            $messageContent,
            UnitMessageListener::ACTION_NAME,
            false,
            false,
            StatisticLogger::STATUS_OK
        );
    }
}