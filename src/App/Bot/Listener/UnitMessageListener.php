<?php

namespace App\Bot\Listener;

use App\Model\Statistic\StatisticLogger;
use App\Model\UnitCalculator\UnitCalculatorReader;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Tracy\Debugger;

class UnitMessageListener
{
    public const ACTION_NAME = 'unit-calculator';

    public function newMessageReceived(Message $message, Discord $discord): void
    {
        if ($message->author !== null && ($message->author->bot === false || $message->author->bot === null))
        {
            $unitCalculatorReader = new UnitCalculatorReader();

            $unitResults = $unitCalculatorReader->readMessage($message->content);

            $messages = [];

            foreach ($unitResults->items as $readerItemResult) {
                $messages[] = $readerItemResult->unitCalculator->createCalculationMessage($readerItemResult->unitCalculatorValue);
            }

            if (empty($messages) === false)
            {
                $message->reply(implode(PHP_EOL, $messages));

                try {
                    StatisticLogger::logUnitCalculatorMessage($message);
                } catch (\Throwable $ex) {
                    Debugger::log($ex);
                }
            }
        }
    }
}