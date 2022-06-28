<?php

namespace App\Command;

use App\Contract\CommandInterface;
use App\Entity\Observation;
use App\Validator\ResourceExists;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteObservationCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Observation::class)]
    public $uuid;
}
