<?php

namespace App\Contract;

use Ramsey\Uuid\UuidInterface;

interface ResourceInterface
{
    public function getUuid(): UuidInterface;
    public function getProvider(): UserInterface;
    public function getData(): array;
    public function getId(): ?int;
}
