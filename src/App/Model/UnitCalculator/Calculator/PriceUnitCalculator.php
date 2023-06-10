<?php

namespace App\Model\UnitCalculator\Calculator;

use App\Config\ConfigLoader;
use App\Model\UnitCalculator\Storage\UnitCalculatorValue;

class PriceUnitCalculator implements IUnitCalculator
{
    private const INTERNAL_CONVERSION_MAP = [
        'kč' => 1,
        'k' => 1000,
        'táců' => 1000,
        'm' => 1000000,
        'míčů' => 1000000,
    ];

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

        $unitsToFind = array_keys(self::INTERNAL_CONVERSION_MAP);

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
        $messageTemplate = $this->config['message'];
        $notEnoughMessageTemplate = $this->config['message-not-enough'];
        $unitTemplate = $this->config['new-unit-name'];
        $conversionRate = $this->config['conversion-rate'];

        $calculatedNewUnit = self::calculateNewUnit($unitCalculatorValue, $conversionRate);

        if ($calculatedNewUnit > 0)
        {
            return strtr($messageTemplate, [
                '<origin-value>' => sprintf('%s %s', $unitCalculatorValue->value, $unitCalculatorValue->unit),
                '<calculated-value>' => sprintf('%s %s', $calculatedNewUnit, $unitTemplate)
            ]);
        }
        else
        {
            return strtr($notEnoughMessageTemplate, [
                '<origin-value>' => sprintf('%s %s', $unitCalculatorValue->value, $unitCalculatorValue->unit)
            ]);
        }
    }

    private static function calculateNewUnit(UnitCalculatorValue $unitCalculatorValue, float $newUnitConversionRate): int
    {
        $internalConversionRate = self::INTERNAL_CONVERSION_MAP[$unitCalculatorValue->unit] ?? 1;
        $baseValue = $unitCalculatorValue->value * $internalConversionRate;

        return floor($baseValue / $newUnitConversionRate);
    }
}