<?php

namespace App\Entity;

use App\Enum\MeasurementType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MissionControlWeather extends Measurement
{
    public function getType(): MeasurementType
    {
        return MeasurementType::MissionControlWeather;
    }
}
