<?php

namespace App\Entity;

use App\Enum\MeasurementType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MissionControlAdsBFlightTracking extends Measurement
{
    public function getType(): MeasurementType
    {
        return MeasurementType::MissionControlAdsBFlightTracking;
    }
}
