<?php

namespace App\Contract;

interface UpdateObservationCommandInterface extends CommandInterface
{
    public function getName(): string;
}
