<?php

namespace App\Command;

use App\Contract\CommandInterface;
use App\Entity\Measurement;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateMeasurementCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Measurement::class)]
    public UuidInterface $uuid;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: Measurement::NAME_MAX_LENGTH)]
    public ?string $name = null;
}
