<?php

namespace App\Command;

use App\Contract\DeleteMeasurementCommandInterface;
use App\Contract\SynchronousCommandInterface;
use App\Contract\UserInterface;
use App\Entity\Measurement;
use App\Validator\ActionOnResourceIsGranted;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteMeasurementCommand implements DeleteMeasurementCommandInterface, SynchronousCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Measurement::class)]
    #[ActionOnResourceIsGranted(entityClassName: Measurement::class)]
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
