<?php

namespace App\Response;

use App\Enum\MeasurementType;

class MeasurementResponse implements \JsonSerializable
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $name,
        public readonly MeasurementType $type,
        public readonly ObservationResponse $observation,
        public readonly ProviderResponse $provider,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'measurementType' => $this->type->value,
            'observation' => $this->observation,
            'provider' => $this->provider,
        ];
    }
}
