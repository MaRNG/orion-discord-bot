<?php

namespace App\Bot\Command\SteamPlayerCount;

use App\Bot\Embed\Generator\SteamGamePlayerEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
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
                $usersCountDto = RequestSender::getUsersCount();
                $messageBuilder = self::createMessage($usersCountDto);
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