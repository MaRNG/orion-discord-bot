<?php

namespace App\Model\UnitCalculator\Storage;

class UnitCalculatorValue
{
    public function __construct(public readonly float $value, public readonly string $unit) { }
}