<?php

namespace App\Response;

use Ramsey\Uuid\UuidInterface;

class UserResponse implements \JsonSerializable
{
    public function __construct(
        private readonly UuidInterface $uuid,
        private readonly string $email,
        private readonly string $name,
        private readonly array $roles,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'name' => $this->name,
            'roles' => $this->roles,
        ];
    }
}
