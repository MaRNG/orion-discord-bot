<?php

namespace App\Model\Database\Handler;

use App\Config\DatabaseLoader;
use App\Config\Storage\DatabaseConfig;
use App\Exception\DatabaseMapperException;
use App\Model\Database\Entity\IEntity;

class EntityHandler
{
    private static ?self $instance = null;

    private \SQLite3 $connection;
    private DatabaseConfig $databaseConfig;
    private EntityMapper $entityMapper;
    private EntityMetadataLoader $entityMetadataLoader;

    private function __construct()
    {
        $this->entityMapper = new EntityMapper();
        $this->databaseConfig = DatabaseLoader::load();
        $this->entityMetadataLoader = new EntityMetadataLoader($this->databaseConfig);

        $this->connection = new \SQLite3($this->databaseConfig->filePath, SQLITE3_OPEN_CREATE |SQLITE3_OPEN_READWRITE);
        $this->connection->enableExceptions($this->databaseConfig->throwsExceptions);

        $this->instantTables();
    }

    public function instantTables(): void
    {
        foreach ($this->databaseConfig->entities as $entity) {
            $refl = new \ReflectionClass($entity['classname']);
            $mappedColumns = [];

            foreach ($refl->getProperties() as $property) {
                if ($property->getType())
                {
                    $propertyTypeName = strtolower($property->getType()->getName());

                    if (isset(EntityMapper::PROPERTY_TYPE_MAP[$propertyTypeName]))
                    {
                        if ($property->getName() === 'id')
                        {
                            $mappedColumns[] = '"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL';
                        }
                        else
                        {
                            $mappedColumns[] = sprintf('"%s" %s', strtolower($property->getName()), EntityMapper::PROPERTY_TYPE_MAP[$propertyTypeName]);
                        }
                    }
                    else
                    {
                        throw new DatabaseMapperException(sprintf('Mapping for property %s type %s is not implemented yet! Entity: %s', $property->getName(), $propertyTypeName, $refl->getName()));
                    }
                }
                else
                {
                    throw new DatabaseMapperException(sprintf('Property %s doesn\'t have type! Entity: %s', $property->getName(), $refl->getName()));
                }
            }

            $implodedMappedColumns = implode(',', $mappedColumns);

            $result = $this->connection->query(sprintf('CREATE TABLE IF NOT EXISTS "%s" (%s)', $entity['table'], $implodedMappedColumns));
            $result->finalize();
        }
    }

    public function insert(IEntity $entity): ?\SQLite3Result
    {
        $mappedEntityValues = $this->entityMapper->mapToDatabaseValues($entity);
        $entityMetadata = $this->entityMetadataLoader->loadFromObject($entity);

        $implodedColumnKeysWithQuotes = implode(', ', array_map(static function(string $columnKey) {
            return sprintf('"%s"', $columnKey);
        }, array_keys($mappedEntityValues)));

        $implodedColumnKeysParameters = implode(', ', array_map(static function(string $columnKey) {
            return sprintf(':%s', $columnKey);
        }, array_keys($mappedEntityValues)));

        $statementInsertSql = sprintf('INSERT INTO "%s" (%s) VALUES (%s)', $entityMetadata['table'], $implodedColumnKeysWithQuotes, $implodedColumnKeysParameters);
        $statement = $this->connection->prepare($statementInsertSql);

        foreach ($mappedEntityValues as $mappedEntityKey => $mappedEntityValue) {
            $statement->bindValue(":{$mappedEntityKey}", $mappedEntityValue);
        }

        $result = $statement->execute();

        $entity->setId($this->connection->lastInsertRowID());

        return $result;
    }

    public function closeConnection(): void
    {
        $this->connection->close();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}