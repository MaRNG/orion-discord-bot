<?php

namespace App\Model\UnitCalculator\Calculator;

use App\Model\UnitCalculator\Storage\UnitCalculatorValue;

interface IUnitCalculator
{
    /**
     * @return array<UnitCalculatorValue>
     */
    public function collectValues(string $message): array;

    public function createCalculationMessage(UnitCalculatorValue $unitCalculatorValue): string;
}