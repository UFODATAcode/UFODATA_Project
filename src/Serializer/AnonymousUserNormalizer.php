<?php

namespace App\Serializer;

use App\Contract\AnonymousUserInterface;
use App\Entity\AnonymousUser;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AnonymousUserNormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): AnonymousUserInterface
    {
        return new AnonymousUser();
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return \is_a($type, AnonymousUserInterface::class, true);
    }
}
