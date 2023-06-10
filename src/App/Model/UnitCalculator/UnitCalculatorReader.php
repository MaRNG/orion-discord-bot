<?php

namespace App\Model\UnitCalculator;

use App\Model\UnitCalculator\Calculator\IUnitCalculator;
use App\Model\UnitCalculator\Calculator\LengthUnitCalculator;
use App\Model\UnitCalculator\Storage\UnitCalculatorReaderItemResult;
use App\Model\UnitCalculator\Storage\UnitCalculatorReaderResult;

class UnitCalculatorReader
{
    /**
     * @var array<IUnitCalculator>
     */
    private array $unitCalculators = [];

    public function __construct()
    {
        $this->registerUnitCalculators();
    }

    public function readMessage(string $message): UnitCalculatorReaderResult
    {
        $results = [];

        foreach ($this->unitCalculators as $unitCalculator)
        {
            $collectedValues = $unitCalculator->collectValues($message);

            foreach ($collectedValues as $collectedValue) {
                $results[] = new UnitCalculatorReaderItemResult($unitCalculator, $collectedValue);
            }
        }

        return new UnitCalculatorReaderResult($results);
    }

    private function registerUnitCalculators(): void
    {
        $this->unitCalculators[] = new LengthUnitCalculator();
    }
}