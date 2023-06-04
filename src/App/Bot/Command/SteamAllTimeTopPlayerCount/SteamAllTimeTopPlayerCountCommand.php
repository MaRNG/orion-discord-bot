<?php

namespace App\Bot\Command\SteamAllTimeTopPlayerCount;

use App\Bot\Embed\Generator\SteamAllTimePlayerCountEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Exception\SteamChartRequestException;
use App\Model\Statistic\StatisticLogger;
use App\SteamChart\Request\Dto\AllTimeTopPlayedGamesDto;
use App\SteamChart\RequestSender;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Tracy\Debugger;

class SteamAllTimeTopPlayerCountCommand
{
    public static function attachCommandListener(Discord $discord): void
    {
        $discord->listenCommand(SteamAllTimeTopPlayerCountCommandRegister::COMMAND_NAME, function(Interaction $interaction) use ($discord) {
            $interaction->acknowledgeWithResponse()->done(function() use ($discord, $interaction) {
                $responseStatusCode = StatisticLogger::STATUS_ERROR;

                try {
                    $allTimeTopPlayedGames = RequestSender::getAllTimeTopPlayedGames();
                    $messageBuilder = self::createMessage($allTimeTopPlayedGames);

                    $responseStatusCode = StatisticLogger::STATUS_OK;
                } catch (SteamChartRequestException $e) {
                    Debugger::log($e);
                    $messageBuilder = MessageErrorFactory::create($e->getMessage());
                }

                try {
                    StatisticLogger::logInteraction($interaction, SteamAllTimeTopPlayerCountCommandRegister::COMMAND_NAME, null, null, null, false, false, $responseStatusCode);
                } catch (\Throwable $ex) {
                    Debugger::log($ex);
                    $interaction->updateOriginalResponse(MessageErrorFactory::create('Statistics logger raised exception!'));
                    return;
                }

                $interaction->updateOriginalResponse($messageBuilder);
            });
        });
    }

    private static function createMessage(AllTimeTopPlayedGamesDto $allTimeTopPlayedGamesDto): MessageBuilder
    {
        $messageBuilder = MessageBuilder::new();

        try {
            $messageBuilder->addEmbed(SteamAllTimePlayerCountEmbedGenerator::generate($allTimeTopPlayedGamesDto));
        } catch (\Exception $ex) {
            Debugger::log($ex);
            $messageBuilder = MessageErrorFactory::create('Wild error has appeared!');
        }

        return $messageBuilder;
    }
}