<?php

namespace App\Bot\Command\SteamCurrentTopPlayerCount;

use App\Bot\Embed\Generator\SteamCurrentPlayerCountEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Exception\SteamChartRequestException;
use App\SteamChart\Request\Dto\CurrentTopPlayedGamesDto;
use App\SteamChart\RequestSender;
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
                try {
                    $currentTopPlayedGames = RequestSender::getCurrentTopPlayedGames();
                    $messageBuilder = self::createMessage($currentTopPlayedGames);
                } catch (SteamChartRequestException $e) {
                    Debugger::log($e);
                    $messageBuilder = MessageErrorFactory::create($e->getMessage());
                }

                $interaction->updateOriginalResponse($messageBuilder);
            });
        });
    }

    private static function createMessage(CurrentTopPlayedGamesDto $usersCountDto): MessageBuilder
    {
        $messageBuilder = MessageBuilder::new();

        try {
            $messageBuilder->addEmbed(SteamCurrentPlayerCountEmbedGenerator::generate($usersCountDto));
        } catch (\Exception $ex) {
            Debugger::log($ex);
            $messageBuilder = MessageErrorFactory::create('Wild error has appeared!');
        }

        return $messageBuilder;
    }
}