<?php

namespace App\Bot\Listener;

use Discord\Discord;
use Discord\WebSockets\Event;

class UnitMessageListenerRegister
{
    public static function register(Discord $discord): UnitMessageListener
    {
        $unitMessageListener = new UnitMessageListener();
        $discord->on(Event::MESSAGE_CREATE, $unitMessageListener->newMessageReceived(...));

        return $unitMessageListener;
    }
}