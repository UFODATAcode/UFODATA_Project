<?php

namespace App\Contract;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;

interface MeasurementInterface
{
    public function getUuid(): UuidInterface;

    public function getObservationUuid(): UuidInterface;

    /**
     * @return ArrayCollection<MeasurementValueInterface>
     */
    public function getValues(): ArrayCollection;
}
