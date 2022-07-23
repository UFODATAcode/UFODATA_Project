<?php

namespace App\Contract;

interface UpdateMeasurementCommandInterface extends CommandInterface
{
    public function getName(): ?string;
}
