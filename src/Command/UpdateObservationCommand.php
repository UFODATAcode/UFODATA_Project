<?php

namespace App\Command;

use App\Contract\CommandInterface;
use App\Entity\Observation;
use App\Validator\ResourceExists;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateObservationCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Observation::class)]
    public $uuid;

    #[Assert\Length(min: 1, max: Observation::NAME_MAX_LENGTH)]
    #[Assert\Type('string')]
    public $name;
}
