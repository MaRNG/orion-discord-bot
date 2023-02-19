<?php

namespace App\Bot\Command\SteamAllTimeTopPlayerCount;

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;

class SteamAllTimeTopPlayerCountCommandRegister
{
    public const COMMAND_NAME = 'steam-all-time-top-games';

    public static function register(Discord $discord): void
    {
        $builder = CommandBuilder::new()
            ->setName(self::COMMAND_NAME)
            ->setDescription('Show most played games all time on Steam.');

        $discord->application->commands->save(new Command($discord, $builder->toArray()));
    }
}