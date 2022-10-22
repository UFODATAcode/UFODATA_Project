<?php

namespace App\Serializer;

use App\Contract\AnonymousUserInterface;
use App\Contract\UserInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserNormalizer implements DenormalizerInterface
{
    public function __construct(
        private readonly Security $security,
    ) {}

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): UserInterface
    {
        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            throw new \Exception('Provided class not implements ' . UserInterface::class);
        }

        return $user;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return \is_a($type, UserInterface::class, true)
            && !\is_a($type, AnonymousUserInterface::class, true);
    }
}
