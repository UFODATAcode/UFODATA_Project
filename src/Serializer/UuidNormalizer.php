<?php

namespace App\Serializer;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UuidNormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): UuidInterface
    {
        return Uuid::fromString($data);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return \is_a($type, UuidInterface::class, true);
    }
}
