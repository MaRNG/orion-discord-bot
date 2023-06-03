<?php

namespace App\Model\Database\Handler;

use App\Exception\DatabaseMapperException;
use App\Model\Database\Entity\IEntity;
use App\Util\StringFormatter;

class EntityMapper
{
    public const PROPERTY_TYPE_MAP = [
        'string' => 'VARCHAR',
        'int' => 'INTEGER',
        'float' => 'FLOAT',
        'datetime' => 'DATETIME',
    ];

    public const DATABASE_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param IEntity $entity
     * @return array<string, string>
     */
    public function mapToDatabaseValues(IEntity $entity): array
    {
        $refl = new \ReflectionClass($entity);

        $mappedValues = [];

        foreach ($refl->getProperties() as $property)
        {
            if ($property->getName() === 'id')
            {
                continue;
            }

            if ($property->getType())
            {
                $propertyTypeName = strtolower($property->getType()->getName());
                $propertyName = strtolower($property->getName());

                $getterFunctionName = $this->getGetterFunctionName($property->getName(), $propertyTypeName);

                if ($refl->hasMethod($getterFunctionName) === false)
                {
                    throw new DatabaseMapperException(sprintf('Getter %s not exists in %s entity', $getterFunctionName, $refl->getName()));
                }

                switch ($propertyTypeName)
                {
                    case 'string':
                        $mappedValues[$propertyName] = self::mapString($entity->$getterFunctionName());
                        break;
                    case 'int':
                        $mappedValues[$propertyName] = self::mapInteger($entity->$getterFunctionName());
                        break;
                    case 'float':
                        $mappedValues[$propertyName] = self::mapFloat($entity->$getterFunctionName());
                        break;
                    case 'datetime':
                        $mappedValues[$propertyName] = self::mapDateTime($entity->$getterFunctionName());
                        break;
                    default:
                        throw new DatabaseMapperException(sprintf('Type %s is not implemented in mapper!', $propertyTypeName));
                }
            }
        }

        return $mappedValues;
    }

    private function getGetterFunctionName(string $columnName, string $columnType): string
    {
        return match ($columnType) {
            'bool' => StringFormatter::toCamelCase(sprintf('is%s', $columnName)),
            default => StringFormatter::toCamelCase(sprintf('get%s', $columnName)),
        };
    }

    private static function mapString(string $input): string
    {
        return $input;
    }

    private static function mapInteger(int $input): string
    {
        return (string)$input;
    }

    private static function mapFloat(float $input): string
    {
        return (string)$input;
    }

    private static function mapDateTime(\DateTime $input): string
    {
        return $input->format(self::DATABASE_DATE_TIME_FORMAT);
    }
}