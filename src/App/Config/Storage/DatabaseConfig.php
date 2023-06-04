<?php

namespace App\Config\Storage;

class DatabaseConfig
{
    public function __construct(
        public readonly string $filePath,
        public readonly array $entities,
        public readonly bool $throwsExceptions
    )
    {
    }
}