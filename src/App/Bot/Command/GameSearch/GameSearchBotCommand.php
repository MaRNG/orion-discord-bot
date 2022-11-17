<?php

namespace App\Bot\Command\GameSearch;

use App\Bot\Embed\Generator\GamePlayerCountEmbedGenerator;
use App\Bot\Embed\Generator\GameSearchEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Exception\DiscordBotSteamException;
use App\Steam\Request\Dto\GameSearchCollectionDto;
use App\Steam\Request\Dto\GameSearchDto;
use App\Steam\RequestSender;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

class GameSearchBotCommand
{
    public static function attachCommandListener(Discord $discord): void
    {
        $discord->listenCommand(GameSearchBotCommandRegister::COMMAND_NAME, function(Interaction $interaction) use ($discord) {
            $interaction->acknowledgeWithResponse()->done(function() use ($discord, $interaction) {
                if (isset($interaction->data->options['game']) && $interaction->data->options['game']->value)
                {
                    $searchGame = $interaction->data->options['game']->value;
                    $gameSearchCollectionDto = RequestSender::getGameSearch($searchGame);

                    $messageBuilder = MessageBuilder::new();

                    if ($gameSearchCollectionDto->count() === 1)
                    {
                        $messageBuilder = self::createPlayerCountMessage($gameSearchCollectionDto->gameSearchList[0]);
                    }
                    elseif ($gameSearchCollectionDto->count() === 0)
                    {
                        $messageBuilder = MessageErrorFactory::create("Game {$searchGame} is not exists!");
                    }
                    else
                    {
                        $limitedGameSearches = array_slice($gameSearchCollectionDto->gameSearchList, 0, 5);
                        $messageBuilder->addEmbed(GameSearchEmbedGenerator::generate(new GameSearchCollectionDto($limitedGameSearches, $searchGame)));

                        $gamesActionRow = ActionRow::new();
                        $otherActionRow = ActionRow::new();

                        $index = 1;
                        foreach ($limitedGameSearches as $limitedGameSearch) {
                            $button = Button::new(Button::STYLE_PRIMARY)->setLabel($index);

                            $button->setListener(function(Interaction $interaction) use ($limitedGameSearch) {
                                $messageBuilder = self::createPlayerCountMessage($limitedGameSearch);

                                $interaction->message->delete();
                                $interaction->channel->sendMessage($messageBuilder);
                            }, $discord);

                            $gamesActionRow->addComponent($button);
                            $index++;
                        }

                        $otherActionRow->addComponent(Button::new(Button::STYLE_DANGER)->setLabel('X')->setListener(function(Interaction $interaction) {
                            $interaction->message->delete();
                            $interaction->channel->sendMessage(MessageBuilder::new()->setContent('All Right Then, Keep Your Secrets :yawning_face:'));
                        }, $discord));

                        $messageBuilder->addComponent($gamesActionRow)->addComponent($otherActionRow);
                    }

                    $interaction->updateOriginalResponse($messageBuilder);
                }
                else
                {
                    $interaction->updateOriginalResponse(MessageErrorFactory::create('Search input data is not found!'));
                }
            });
        });
    }

    private static function createPlayerCountMessage(GameSearchDto $gameSearchDto): MessageBuilder
    {
        $messageBuilder = MessageBuilder::new();

        try {
            $steamId = $gameSearchDto->getSteamId();

            $playerCountDto = RequestSender::getPlayerCount($steamId);
            $gameDetailDto = RequestSender::getGameDetail($steamId);

            $messageBuilder->addEmbed(GamePlayerCountEmbedGenerator::generate($gameDetailDto, $playerCountDto, $gameSearchDto));
        } catch (DiscordBotSteamException $ex) {
            $messageBuilder = MessageErrorFactory::create($ex->getMessage());
        } catch (\Exception $ex) {
            $messageBuilder = MessageErrorFactory::create('Wild error has appeared!');
        }

        return $messageBuilder;
    }
}