<?php

namespace App\Model\UnitCalculator\Calculator;

use App\Model\UnitCalculator\Storage\ConversionUnit;
use App\Model\UnitCalculator\Storage\UnitCalculatorValue;

abstract class BaseUnitCalculator implements IUnitCalculator
{
    protected const INTERNAL_CONVERSION_MAP = [];

    public function __construct(private readonly array $config)
    {
    }

    /**
     * Collect values from received message
     * @return array<\App\Model\UnitCalculator\Storage\UnitCalculatorValue>
     */
    public function collectValues(string $message): array
    {
        $unitCalculatorValues = [];

        $unitsToFind = array_keys(static::INTERNAL_CONVERSION_MAP);

        preg_match_all(sprintf('/([0-9.,]+)\s?(%s)\b/iu', implode('|', $unitsToFind)), $message, $matches);

        $values = array_values($matches[1]);
        $units = array_values($matches[2]);

        foreach ($values as $valueKey => $value) {
            $value = (float)$value;
            $unit = $units[$valueKey] ?? null;

            if ($value > 0 && $unit !== null)
            {
                $unitCalculatorValues[] = new UnitCalculatorValue($value, $unit);
            }
        }

        return $unitCalculatorValues;
    }

    public function createCalculationMessage(UnitCalculatorValue $unitCalculatorValue): string
    {
        $conversionUnits = $this->getConversionUnits();
        $conversionUnitKey = array_rand($conversionUnits, 1);

        /** @var ConversionUnit $conversionUnit */
        $conversionUnit = empty($conversionUnitKey) ? null : $conversionUnits[$conversionUnitKey];

        if ($conversionUnit !== null)
        {
            $calculatedNewUnit = self::calculateNewUnit($unitCalculatorValue, $conversionUnit->conversionRate);

            return strtr($conversionUnit->message, [
                '<origin-value>' => sprintf('%s %s', $unitCalculatorValue->value, $unitCalculatorValue->unit),
                '<calculated-value>' => sprintf('%s %s', $calculatedNewUnit, $conversionUnit->newUnitName)
            ]);
        }
        else
        {
            return 'Conversion unit failed! SADGE';
        }
    }

    protected static function calculateNewUnit(UnitCalculatorValue $unitCalculatorValue, float $newUnitConversionRate): float
    {
        if ($newUnitConversionRate === 0.0)
        {
            return 0;
        }

        $internalConversionRate = static::INTERNAL_CONVERSION_MAP[$unitCalculatorValue->unit] ?? 1;
        $baseValue = $unitCalculatorValue->value * $internalConversionRate;

        return round($baseValue / $newUnitConversionRate, 3);
    }

    protected function getConversionUnits(): array
    {
        return array_map(static function(array $unit) {
            return new ConversionUnit($unit['conversion-rate'], $unit['message'], $unit['new-unit-name']);
        }, $this->config['units'] ?? []);
    }
}