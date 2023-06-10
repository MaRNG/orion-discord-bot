<?php

namespace App\Model\UnitCalculator\Calculator;

class PriceUnitCalculator extends BaseUnitCalculator
{
    protected const INTERNAL_CONVERSION_MAP = [
        'kč' => 1,
        'k' => 1000,
        'táců' => 1000,
        'tisíc' => 1000,
        'míčů' => 1000000,
        'milion' => 1000000,
        'milions' => 1000000,
        'milionů' => 1000000,
    ];
}