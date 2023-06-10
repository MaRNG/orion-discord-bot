<?php

namespace App\Model\UnitCalculator\Storage;

class ConversionUnit
{
    public function __construct(
        public readonly float $conversionRate,
        public readonly string $message,
        public readonly string $newUnitName
    )
    {
    }
}