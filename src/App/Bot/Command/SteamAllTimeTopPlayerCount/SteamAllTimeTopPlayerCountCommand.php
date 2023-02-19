<?php

namespace App\Bot\Command\SteamAllTimeTopPlayerCount;

use App\Bot\Embed\Generator\SteamAllTimePlayerCountEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Exception\SteamChartRequestException;
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
                try {
                    $allTimeTopPlayedGames = RequestSender::getAllTimeTopPlayedGames();
                    $messageBuilder = self::createMessage($allTimeTopPlayedGames);
                } catch (SteamChartRequestException $e) {
                    Debugger::log($e);
                    $messageBuilder = MessageErrorFactory::create($e->getMessage());
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