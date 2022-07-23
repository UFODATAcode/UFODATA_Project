<?php

namespace App\Response;

use App\Enum\MeasurementType;

class MeasurementResponse implements \JsonSerializable
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $name,
        private readonly MeasurementType $type,
        private readonly ObservationResponse $observation,
        private readonly ProviderResponse $provider,
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
