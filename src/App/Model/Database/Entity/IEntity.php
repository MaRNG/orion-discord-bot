<?php

namespace App\Model\Database\Entity;

interface IEntity
{
    public function getId(): ?int;

    public function setId(int $id): static ;
}