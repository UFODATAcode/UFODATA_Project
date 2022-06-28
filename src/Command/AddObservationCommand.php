<?php

namespace App\Command;

use App\Contract\CommandInterface;
use App\Entity\Observation;
use App\Validator\ResourceNotExists;
use Symfony\Component\Validator\Constraints as Assert;

class AddObservationCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceNotExists(entityClassName: Observation::class)]
    public $uuid;

    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 64)]
    #[Assert\Type('string')]
    public $name;
}
