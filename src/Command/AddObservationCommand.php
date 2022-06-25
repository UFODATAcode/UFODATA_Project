<?php

namespace App\Command;

use App\Contract\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AddObservationCommand implements CommandInterface
{
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Assert\Length(min: 36, max: 36)]
    #[Assert\Uuid]
    public $uuid;

    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 144)]
    #[Assert\Type('string')]
    public $name;
}
