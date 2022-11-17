<?php

namespace App\Bot\Command\GameSearch;

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Interactions\Command\Option;

class GameSearchBotCommandRegister
{
    public const COMMAND_NAME = 'player-count';

    public static function register(Discord $discord): void
    {
        $builder = CommandBuilder::new()
            ->setName(self::COMMAND_NAME)
            ->setDescription('Show player count by game name.');

        $builder->addOption(
            (new Option($discord))
                ->setName('game')
                ->setDescription('Game Name')
                ->setType(Option::STRING)
                ->setRequired(true)
        );

        $discord->application->commands->save(new Command($discord, $builder->toArray()));
    }
}