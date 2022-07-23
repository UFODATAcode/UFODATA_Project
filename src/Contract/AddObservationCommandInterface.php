<?php

namespace App\Contract;

interface AddObservationCommandInterface extends CommandInterface
{
    public function getName(): string;
}
