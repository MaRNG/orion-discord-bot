<?php

namespace App\Model\UnitCalculator\Storage;

class UnitCalculatorReaderResult
{
    /**
     * @param array<UnitCalculatorReaderItemResult> $items
     */
    public function __construct(public readonly array $items)
    {
    }
}