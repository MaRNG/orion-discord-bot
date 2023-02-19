<?php

namespace App\Bot;

use App\Bot\Command\GameSearch\GameSearchBotCommand;
use App\Bot\Command\GameSearch\GameSearchBotCommandRegister;
use App\Bot\Command\Info\AboutBotCommand;
use App\Bot\Command\Info\AboutBotCommandRegister;
use App\Bot\Command\SteamAllTimeTopPlayerCount\SteamAllTimeTopPlayerCountCommand;
use App\Bot\Command\SteamAllTimeTopPlayerCount\SteamAllTimeTopPlayerCountCommandRegister;
use App\Bot\Command\SteamCurrentTopPlayerCount\SteamCurrentTopPlayerCountCommand;
use App\Bot\Command\SteamCurrentTopPlayerCount\SteamCurrentTopPlayerCountCommandRegister;
use App\Bot\Command\SteamPlayerCount\SteamPlayerCountBotCommand;
use App\Bot\Command\SteamPlayerCount\SteamPlayerCountBotCommandRegister;
use App\Config\CredentialsLoader;
use Discord\Discord;
use Discord\Parts\User\Activity;

class SteamBot
{
    private Discord $discord;

    public function run(): void
    {
        $credentials = CredentialsLoader::load();

        $this->discord = new Discord(['token' => $credentials->getToken()]);

        $this->discord->on('ready', static function(Discord $discord) {
            echo 'Starting bot...' . PHP_EOL;

            $discord->application->commands->clear();

            $activity = new Activity($discord, [
                'type' => Activity::TYPE_LISTENING,
                'name' => '/player-count'
            ]);

            $discord->updatePresence($activity);

            GameSearchBotCommandRegister::register($discord);
            GameSearchBotCommand::attachCommandListener($discord);

            AboutBotCommandRegister::register($discord);
            AboutBotCommand::attachCommandListener($discord);

            SteamPlayerCountBotCommandRegister::register($discord);
            SteamPlayerCountBotCommand::attachCommandListener($discord);

            SteamAllTimeTopPlayerCountCommandRegister::register($discord);
            SteamAllTimeTopPlayerCountCommand::attachCommandListener($discord);

            SteamCurrentTopPlayerCountCommandRegister::register($discord);
            SteamCurrentTopPlayerCountCommand::attachCommandListener($discord);

            echo 'Bot is ready!' . PHP_EOL;
        });

        $this->discord->run();
    }
}