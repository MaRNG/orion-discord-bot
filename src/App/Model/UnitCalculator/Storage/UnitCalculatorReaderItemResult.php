<?php

namespace App\Model\UnitCalculator\Storage;

use App\Model\UnitCalculator\Calculator\IUnitCalculator;

class UnitCalculatorReaderItemResult
{
    public function __construct(
        public readonly IUnitCalculator $unitCalculator,
        public readonly UnitCalculatorValue $unitCalculatorValue
    )
    {
    }
}