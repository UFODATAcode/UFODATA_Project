<?php

namespace App\ValueObject;

class Error implements \JsonSerializable
{
    public function __construct(
        private readonly string $property,
        private readonly string $message,
        private readonly string $code,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'property' => $this->property,
            'message' => $this->message,
            'code' => $this->code,
        ];
    }
}
