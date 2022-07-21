<?php

namespace App\Event;

use App\Document\Measurement\Measurement;

class MeasurementUploadedEvent
{
    public const NAME = 'measurement.uploaded';

    public function __construct(
        private readonly Measurement $measurement,
    ) {}

    public function getMeasurement(): Measurement
    {
        return $this->measurement;
    }
}
