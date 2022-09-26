<?php

namespace App\Response;

use Ramsey\Uuid\UuidInterface;

class ProviderResponse implements \JsonSerializable
{
    public function __construct(
        public readonly UuidInterface $uuid,
        public readonly string $name,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}
