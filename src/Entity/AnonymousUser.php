<?php

namespace App\Entity;

use App\Contract\AnonymousUserInterface;
use App\Contract\UserInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AnonymousUser implements AnonymousUserInterface
{
    public function getUuid(): UuidInterface
    {
        return Uuid::fromString('784a9ac1-ac78-45df-bb8c-aaabd6872717');
    }

    public function getProvider(): UserInterface
    {
        return $this;
    }

    public function getData(): array
    {
        return [];
    }

    public function getId(): ?int
    {
        return null;
    }

    public function getName(): string
    {
        return '';
    }

    public function isActive(): bool
    {
        return true;
    }

    public function getEmail(): string
    {
        return '';
    }

    public function activate(): UserInterface
    {
        return $this;
    }
}