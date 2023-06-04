<?php

namespace App\Bot\Command\SteamCurrentTopPlayerCount;

use App\Bot\Embed\Generator\SteamCurrentPlayerCountEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Exception\SteamChartRequestException;
use App\Model\Statistic\StatisticLogger;
use App\Steam\Request\Dto\CurrentTopPlayedGamesResponseDto;
use App\Steam\RequestSender;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Tracy\Debugger;

class SteamCurrentTopPlayerCountCommand
{
    public static function attachCommandListener(Discord $discord): void
    {
        $discord->listenCommand(SteamCurrentTopPlayerCountCommandRegister::COMMAND_NAME, function(Interaction $interaction) use ($discord) {
            $interaction->acknowledgeWithResponse()->done(function() use ($discord, $interaction) {
                $responseStatusCode = StatisticLogger::STATUS_ERROR;

                try {
                    $currentTopPlayedGames = RequestSender::getCurrentTopPlayedGames();
                    $messageBuilder = self::createMessage($currentTopPlayedGames);

                    $responseStatusCode = StatisticLogger::STATUS_OK;
                } catch (SteamChartRequestException $e) {
                    Debugger::log($e);
                    $messageBuilder = MessageErrorFactory::create($e->getMessage());
                }

                try {
                    StatisticLogger::logInteraction($interaction, SteamCurrentTopPlayerCountCommandRegister::COMMAND_NAME, null, null, null, false, false, $responseStatusCode);
                } catch (\Throwable $ex) {
                    Debugger::log($ex);
                    $interaction->updateOriginalResponse(MessageErrorFactory::create('Statistics logger raised exception!'));
                    return;
                }

                $interaction->updateOriginalResponse($messageBuilder);
            });
        });
    }

    private static function createMessage(CurrentTopPlayedGamesResponseDto $currentTopPlayedGamesResponseDto): MessageBuilder
    {
        $messageBuilder = MessageBuilder::new();

        try {
            $messageBuilder->addEmbed(SteamCurrentPlayerCountEmbedGenerator::generate($currentTopPlayedGamesResponseDto));
        } catch (\Exception $ex) {
            Debugger::log($ex);
            $messageBuilder = MessageErrorFactory::create('Wild error has appeared!');
        }

        return $messageBuilder;
    }
}