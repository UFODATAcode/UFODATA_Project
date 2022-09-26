<?php

namespace App\Command;

use App\Contract\UpdateMeasurementCommandInterface;
use App\Contract\UserInterface;
use App\Entity\Measurement;
use App\Validator\ActionOnResourceIsGranted;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateMeasurementCommand implements UpdateMeasurementCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Measurement::class)]
    #[ActionOnResourceIsGranted(entityClassName: Measurement::class)]
    public UuidInterface $uuid;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: Measurement::NAME_MAX_LENGTH)]
    public ?string $name = null;

    #[Ignore]
    public UserInterface $provider;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
