<?php

namespace App\Command;

use App\Contract\UpdateObservationCommandInterface;
use App\Contract\UserInterface;
use App\Entity\Observation;
use App\Validator\ActionOnResourceIsGranted;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateObservationCommand implements UpdateObservationCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Observation::class)]
    #[ActionOnResourceIsGranted(entityClassName: Observation::class)]
    public UuidInterface $uuid;

    #[Assert\Length(min: 1, max: Observation::NAME_MAX_LENGTH)]
    #[Assert\Type('string')]
    public string $name;

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

    public function getName(): string
    {
        return $this->name;
    }
}
