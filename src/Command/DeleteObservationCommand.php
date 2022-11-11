<?php

namespace App\Command;

use App\Contract\DeleteObservationCommandInterface;
use App\Contract\SynchronousCommandInterface;
use App\Contract\UserInterface;
use App\Entity\Observation;
use App\Validator\ActionOnResourceIsGranted;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteObservationCommand implements DeleteObservationCommandInterface, SynchronousCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Observation::class)]
    #[ActionOnResourceIsGranted(entityClassName: Observation::class)]
    public UuidInterface $uuid;

    public UserInterface $provider;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }
}
