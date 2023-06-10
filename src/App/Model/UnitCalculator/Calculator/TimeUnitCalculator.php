<?php

namespace App\Model\UnitCalculator\Calculator;

class TimeUnitCalculator extends BaseUnitCalculator
{
    protected const INTERNAL_CONVERSION_MAP = [
        'hodina' => 1,
        'hodinu' => 1,
        'h' => 1,
        'hodin' => 1,
        'hodiny' => 1,
        'hour' => 1,
        'hours' => 1,
        'min' => 1/60,
        'minuta' => 1/60,
        'minut' => 1/60,
        'minuty' => 1/60,
        'minutu' => 1/60,
        'minutes' => 1/60,
        'minute' => 1/60,
        's' => 1/60/60,
        'vteřin' => 1/60/60,
        'vteřina' => 1/60/60,
        'vteřiny' => 1/60/60,
        'vteřinu' => 1/60/60,
        'sekund' => 1/60/60,
        'sekundy' => 1/60/60,
        'sekundu' => 1/60/60,
        'second' => 1/60/60,
        'seconds' => 1/60/60,
        'den' => 24,
        'dní' => 24,
        'dnů' => 24,
        'day' => 24,
        'days' => 24,
        'měsíc' => 24*30,
        'měsíců' => 24*30,
        'month' => 24*30,
        'months' => 24*30,
        'rok' => 24*30*365,
        'roků' => 24*30*365,
        'let' => 24*30*365,
        'year' => 24*30*365,
        'years' => 24*30*365,
    ];
}