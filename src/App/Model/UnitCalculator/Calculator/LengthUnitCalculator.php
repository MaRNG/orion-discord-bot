<?php

namespace App\Model\UnitCalculator\Calculator;

class LengthUnitCalculator extends BaseUnitCalculator
{
    protected const INTERNAL_CONVERSION_MAP = [
        'nm' => 1/1000000000,
        'nanometr' => 1/1000000000,
        'nanometrů' => 1/1000000000,
        'nanometer' => 1/1000000000,
        'µm' => 1/1000000,
        'mikrometr' => 1/1000000,
        'mikrometrů' => 1/1000000,
        'mikrometer' => 1/1000000,
        'mm' => 1/1000,
        'milimetr' => 1/1000,
        'milimetrů' => 1/1000,
        'milimeter' => 1/1000,
        'cm' => 1/100,
        'centimetr' => 1/100,
        'centimetrů' => 1/100,
        'centimeter' => 1/100,
        'dm' => 1/10,
        'decimetr' => 1/10,
        'decimetrů' => 1/10,
        'decimeter' => 1/10,
        'm' => 1,
        'metr' => 1,
        'metrů' => 1,
        'meter' => 1,
        'km' => 1000,
        'kilometr' => 1000,
        'kilometrů' => 1000,
        'kilometer' => 1000,
    ];
}