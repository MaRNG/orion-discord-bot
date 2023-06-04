<?php

namespace App\Model\Database\Handler;

use App\Config\Storage\DatabaseConfig;
use App\Exception\DatabaseMetadataNotFoundException;
use App\Model\Database\Entity\IEntity;

class EntityMetadataLoader
{
    private DatabaseConfig $databaseConfig;

    public function __construct(DatabaseConfig $databaseConfig)
    {
        $this->databaseConfig = $databaseConfig;
    }

    public function loadFromObject(IEntity $entity): array
    {
        return $this->loadFromClassname($entity::class);
    }

    public function loadFromClassname(string $classname): array
    {
        foreach ($this->databaseConfig->entities as $entity) {
            if ($entity['classname'] === $classname)
            {
                return $entity;
            }
        }

        throw new DatabaseMetadataNotFoundException(sprintf('Metadata for classname %s not found!', $classname));
    }
}