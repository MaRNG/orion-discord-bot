<?php

namespace App\Model\UnitCalculator\Calculator;

class PriceUnitCalculator extends BaseUnitCalculator
{
    protected const INTERNAL_CONVERSION_MAP = [
        'kč' => 1,
        'k' => 1000,
        'táců' => 1000,
        'm' => 1000000,
        'míčů' => 1000000,
    ];
}