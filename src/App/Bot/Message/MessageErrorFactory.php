<?php

namespace App\Bot\Message;

use Discord\Builders\MessageBuilder;

class MessageErrorFactory
{
    public static function create(string $message): MessageBuilder
    {
        $builder = MessageBuilder::new();

        $builder->setContent("[ERROR] :x: {$message} :x:");

        return $builder;
    }
}