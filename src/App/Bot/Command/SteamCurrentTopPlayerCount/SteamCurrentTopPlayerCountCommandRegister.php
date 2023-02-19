<?php

namespace App\Bot\Command\SteamCurrentTopPlayerCount;

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;

class SteamCurrentTopPlayerCountCommandRegister
{
    public const COMMAND_NAME = 'steam-current-top-games';

    public static function register(Discord $discord): void
    {
        $builder = CommandBuilder::new()
            ->setName(self::COMMAND_NAME)
            ->setDescription('Show currently most played games on Steam.');

        $discord->application->commands->save(new Command($discord, $builder->toArray()));
    }
}