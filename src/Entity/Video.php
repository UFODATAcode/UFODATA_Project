<?php

namespace App\Entity;

use App\Enum\MeasurementType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Video extends Measurement
{
    public function getType(): MeasurementType
    {
        return MeasurementType::Video;
    }
}
