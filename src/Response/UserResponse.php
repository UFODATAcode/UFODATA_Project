<?php

namespace App\Response;

use Ramsey\Uuid\UuidInterface;

class UserResponse implements \JsonSerializable
{
    public function __construct(
        private readonly UuidInterface $uuid,
        private readonly string $name,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}
