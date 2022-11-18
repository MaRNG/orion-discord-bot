<?php

namespace App\Bot\Command\Info;

use App\Bot\Embed\Generator\AboutBotEmbedGenerator;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;

class AboutBotCommand
{
    public static function attachCommandListener(Discord $discord): void
    {
        $discord->listenCommand(AboutBotCommandRegister::COMMAND_NAME, function(Interaction $interaction) {
            $messageBuilder = MessageBuilder::new();

            $messageBuilder->addEmbed(AboutBotEmbedGenerator::generate());

            $interaction->respondWithMessage($messageBuilder);
        });
    }
}