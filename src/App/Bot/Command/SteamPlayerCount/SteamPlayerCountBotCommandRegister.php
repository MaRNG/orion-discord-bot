<?php

namespace App\Bot\Command\SteamPlayerCount;

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;

class SteamPlayerCountBotCommandRegister
{
    public const COMMAND_NAME = 'steam-player-count';

    public static function register(Discord $discord): void
    {
        $builder = CommandBuilder::new()
            ->setName(self::COMMAND_NAME)
            ->setDescription('Show player count on Steam.');

        $discord->application->commands->save(new Command($discord, $builder->toArray()));
    }
}