<?php

namespace App\Bot\Command\GameSearch;

use App\Bot\Embed\Generator\GamePlayerCountEmbedGenerator;
use App\Bot\Embed\Generator\GameSearchEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Bot\Message\PlayerCountMessage;
use App\Exception\DiscordBotSteamException;
use App\Model\Statistic\StatisticLogger;
use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\GameSearchDto;
use App\Steam\RequestSender;
use App\Util\GameReviewsCalculator;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use Tracy\Debugger;

class GameSearchBotCommand
{
    public static function attachCommandListener(Discord $discord): void
    {
        $discord->listenCommand(GameSearchBotCommandRegister::COMMAND_NAME, function(Interaction $interaction) use ($discord) {
            $interaction->acknowledgeWithResponse()->done(function() use ($discord, $interaction) {
                $responseStatusCodeForStatistics = StatisticLogger::STATUS_ERROR;
                $foundGameName = null;
                $foundGamePlayerCount = null;
                $foundGameReviewsPositive = null;
                $foundTooManyGames = false;

                if (isset($interaction->data->options['game']) && $interaction->data->options['game']->value)
                {
                    $searchGame = $interaction->data->options['game']->value;

                    try {
                        $gameSearchCollectionDto = RequestSender::getGameSearch($searchGame);
                    } catch (\Throwable $ex) {
                        Debugger::log($ex);
                        $interaction->updateOriginalResponse(MessageErrorFactory::create('Steam API raised exception!'));
                        return;
                    }

                    $messageBuilder = MessageBuilder::new();

                    if ($gameSearchCollectionDto->count() === 1)
                    {
                        $playerCountMessage = self::createPlayerCountMessage($gameSearchCollectionDto->gameSearchList[0]);
                        $messageBuilder = $playerCountMessage->messageBuilder;
                        $responseStatusCodeForStatistics = StatisticLogger::STATUS_OK;

                        $foundGameName = $playerCountMessage->gameDetailDto?->gameName;
                        $foundGamePlayerCount = $playerCountMessage->gamePlayerCountDto?->playerCount;
                        $foundGameReviewsPositive = $playerCountMessage->gameReviewsDto ? GameReviewsCalculator::calculatePositivePercent($playerCountMessage->gameReviewsDto->totalReviews, $playerCountMessage->gameReviewsDto->positiveReviews) : null;
                    }
                    elseif ($gameSearchCollectionDto->count() === 0)
                    {
                        $messageBuilder = MessageErrorFactory::create("Game {$searchGame} is not exists!");
                        $responseStatusCodeForStatistics = StatisticLogger::STATUS_NOT_FOUND;
                    }
                    else
                    {
                        $foundTooManyGames = true;

                        $responseStatusCodeForStatistics = StatisticLogger::STATUS_OK;

                        $limitedGameSearches = array_slice($gameSearchCollectionDto->gameSearchList, 0, 5);
                        $messageBuilder->addEmbed(GameSearchEmbedGenerator::generate(new GameSearchCollectionDto($limitedGameSearches, $searchGame)));

                        $gamesActionRow = ActionRow::new();
                        $otherActionRow = ActionRow::new();

                        $index = 1;
                        foreach ($limitedGameSearches as $limitedGameSearch) {
                            $button = Button::new(Button::STYLE_PRIMARY)->setLabel((string)$index);

                            $button->setListener(function(Interaction $interaction) use ($limitedGameSearch) {
                                $playerCountMessage = self::createPlayerCountMessage($limitedGameSearch);

                                $foundGameName = $playerCountMessage->gameDetailDto?->gameName;
                                $foundGamePlayerCount = $playerCountMessage->gamePlayerCountDto?->playerCount;
                                $foundGameReviewsPositive = $playerCountMessage->gameReviewsDto ? GameReviewsCalculator::calculatePositivePercent($playerCountMessage->gameReviewsDto->totalReviews, $playerCountMessage->gameReviewsDto->positiveReviews) : null;

                                $messageBuilder = $playerCountMessage->messageBuilder;

                                $interaction->message?->delete();
                                $interaction->channel?->sendMessage($messageBuilder);

                                try {
                                    StatisticLogger::logInteraction($interaction, GameSearchBotCommandRegister::COMMAND_NAME, $foundGameName, $foundGamePlayerCount, $foundGameReviewsPositive, false, true, StatisticLogger::STATUS_OK);
                                } catch (\Throwable $ex) {
                                    Debugger::log($ex);
                                    $interaction->updateOriginalResponse(MessageErrorFactory::create('Statistics logger raised exception!'));
                                    return;
                                }
                            }, $discord);

                            $gamesActionRow->addComponent($button);
                            $index++;
                        }

                        $otherActionRow->addComponent(Button::new(Button::STYLE_DANGER)->setLabel('X')->setListener(function(Interaction $interaction) {
                            $interaction->message?->delete();
                            $interaction->channel?->sendMessage(MessageBuilder::new()->setContent('All Right Then, Keep Your Secrets :yawning_face:'));
                        }, $discord));

                        $messageBuilder->addComponent($gamesActionRow)->addComponent($otherActionRow);
                    }

                    $interaction->updateOriginalResponse($messageBuilder);
                }
                else
                {
                    $interaction->updateOriginalResponse(MessageErrorFactory::create('Search input data is not found!'));
                    $responseStatusCodeForStatistics = StatisticLogger::STATUS_BAD_INPUT;
                }

                try {
                    StatisticLogger::logInteraction($interaction, GameSearchBotCommandRegister::COMMAND_NAME, $foundGameName, $foundGamePlayerCount, $foundGameReviewsPositive, $foundTooManyGames, false, $responseStatusCodeForStatistics);
                } catch (\Throwable $ex) {
                    Debugger::log($ex);
                    $interaction->updateOriginalResponse(MessageErrorFactory::create('Statistics logger raised exception!'));
                    return;
                }
            });
        });
    }

    private static function createPlayerCountMessage(GameSearchDto $gameSearchDto): PlayerCountMessage
    {
        $messageBuilder = MessageBuilder::new();

        try {
            $steamId = $gameSearchDto->getSteamId();

            $playerCountDto = RequestSender::getPlayerCount($steamId);
            $gameDetailDto = RequestSender::getGameDetail($steamId);
            $gameReviewsDto = RequestSender::getGameReviews($steamId);

            $messageBuilder->addEmbed(GamePlayerCountEmbedGenerator::generate($gameDetailDto, $playerCountDto, $gameSearchDto, $gameReviewsDto));
        } catch (DiscordBotSteamException $ex) {
            Debugger::log($ex);
            $messageBuilder = MessageErrorFactory::create($ex->getMessage());
        } catch (BadResponseException $ex) {
            Debugger::log($ex);
            $messageBuilder = MessageErrorFactory::create("Game {$gameSearchDto->getName()} hasn\'t info about current player count. :sob:");
        } catch (\Throwable $ex) {
            Debugger::log($ex);
            $messageBuilder = MessageErrorFactory::create('Wild error has appeared!');
        }

        return new PlayerCountMessage($messageBuilder, $playerCountDto ?? null, $gameDetailDto ?? null, $gameReviewsDto ?? null);
    }
}