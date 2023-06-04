<?php

namespace App\Bot\Command\Info;

use App\Bot\Embed\Generator\AboutBotEmbedGenerator;
use App\Bot\Message\MessageErrorFactory;
use App\Model\Statistic\StatisticLogger;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Tracy\Debugger;

class AboutBotCommand
{
    public static function attachCommandListener(Discord $discord): void
    {
        $discord->listenCommand(AboutBotCommandRegister::COMMAND_NAME, function(Interaction $interaction) {
            $messageBuilder = MessageBuilder::new();

            $messageBuilder->addEmbed(AboutBotEmbedGenerator::generate());

            $interaction->respondWithMessage($messageBuilder);

            try {
                StatisticLogger::logInteraction($interaction, AboutBotCommandRegister::COMMAND_NAME, null, null, null, false, false, StatisticLogger::STATUS_OK);
            } catch (\Throwable $ex) {
                Debugger::log($ex);
                $interaction->updateOriginalResponse(MessageErrorFactory::create('Statistics logger raised exception!'));
                return;
            }
        });
    }
}