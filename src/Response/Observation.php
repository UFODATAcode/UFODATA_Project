<?php

namespace App\Response;

class Observation implements \JsonSerializable
{
    public function __construct(
        private readonly string $uuid,
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
