<?php

namespace App\Command;

use App\Contract\AddObservationCommandInterface;
use App\Contract\SynchronousCommandInterface;
use App\Contract\UserInterface;
use App\Entity\Observation;
use App\Validator\ResourceNotExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AddObservationCommand implements AddObservationCommandInterface, SynchronousCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceNotExists(entityClassName: Observation::class)]
    public UuidInterface $uuid;

    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: Observation::NAME_MAX_LENGTH)]
    #[Assert\Type('string')]
    public string $name;

    public UserInterface $provider;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }
}
