<?php

namespace App\Model\UnitCalculator\Calculator;

class WeightUnitCalculator extends BaseUnitCalculator
{
    protected const INTERNAL_CONVERSION_MAP = [
        'kg' => 1,
        'kilogramů' => 1,
        'kilogram' => 1,
        'kilo' => 1,
        'g' => 1/1000,
        'gram' => 1/1000,
        'gramů' => 1/1000,
        'dkg' => 1/100,
        'deka' => 1/100,
        'dekagramů' => 1/100,
        'miligram' => 1/1000000,
        'miligramů' => 1/1000000,
        'mg' => 1/1000000,
        'tun' => 1000,
        't' => 1000,
        'ton' => 1000,
        'tons' => 1000,
    ];
}