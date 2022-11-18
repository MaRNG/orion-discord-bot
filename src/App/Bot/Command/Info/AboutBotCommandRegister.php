<?php

namespace App\Bot\Command\Info;

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;

class AboutBotCommandRegister
{
    public const COMMAND_NAME = 'about';

    public static function register(Discord $discord): void
    {
        $builder = CommandBuilder::new()
            ->setName(self::COMMAND_NAME)
            ->setDescription('Show info about bot');

        $discord->application->commands->save(new Command($discord, $builder->toArray()));
    }
}