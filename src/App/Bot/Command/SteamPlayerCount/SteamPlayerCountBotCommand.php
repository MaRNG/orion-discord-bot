<?php

namespace App\Bot\Command\SteamPlayerCount;

use App\Bot\Embed\Generator\SteamGamePlayerEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Model\Statistic\StatisticLogger;
use App\Steam\Request\Dto\UsersCountDto;
use App\Steam\RequestSender;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Tracy\Debugger;

class SteamPlayerCountBotCommand
{
    public static function attachCommandListener(Discord $discord): void
    {
        $discord->listenCommand(SteamPlayerCountBotCommandRegister::COMMAND_NAME, function(Interaction $interaction) use ($discord) {
            $interaction->acknowledgeWithResponse()->done(function() use ($discord, $interaction) {
                $responseStatusCode = StatisticLogger::STATUS_ERROR;

                try {
                    $usersCountDto = RequestSender::getUsersCount();
                    $messageBuilder = self::createMessage($usersCountDto);

                    $responseStatusCode = StatisticLogger::STATUS_OK;
                } catch (\Throwable $ex) {
                    $messageBuilder = MessageErrorFactory::create('Steam player count data couldn\'t be fetch!');
                }

                try {
                    StatisticLogger::logInteraction($interaction, SteamPlayerCountBotCommandRegister::COMMAND_NAME, null, null, null, false, false, $responseStatusCode);
                } catch (\Throwable $ex) {
                    Debugger::log($ex);
                    $interaction->updateOriginalResponse(MessageErrorFactory::create('Statistics logger raised exception!'));
                    return;
                }

                $interaction->updateOriginalResponse($messageBuilder);
            });
        });
    }

    private static function createMessage(UsersCountDto $usersCountDto): MessageBuilder
    {
        $messageBuilder = MessageBuilder::new();

        try {
            $messageBuilder->addEmbed(SteamGamePlayerEmbedGenerator::generate($usersCountDto));
        } catch (\Exception $ex) {
            Debugger::log($ex);
            $messageBuilder = MessageErrorFactory::create('Wild error has appeared!');
        }

        return $messageBuilder;
    }
}