<?php

namespace App\Response;

class ObservationResponse implements \JsonSerializable
{
    public function __construct(
        public readonly string $uuid,
        public readonly ?string $name,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}
