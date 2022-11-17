<?php

namespace App\Bot;

use App\Bot\Command\GameSearch\GameSearchBotCommand;
use App\Bot\Command\GameSearch\GameSearchBotCommandRegister;
use App\Config\CredentialsLoader;
use Discord\Discord;

class SteamBot
{
    private Discord $discord;

    public function run(): void
    {
        $credentials = CredentialsLoader::load();

        $this->discord = new Discord(['token' => $credentials->getToken()]);

        $this->discord->on('ready', static function(Discord $discord) {
            echo 'Starting bot...' . PHP_EOL;

            GameSearchBotCommandRegister::register($discord);
            GameSearchBotCommand::attachCommandListener($discord);

            echo 'Bot is ready!' . PHP_EOL;
        });

        $this->discord->run();
    }
}