<?php

namespace App\Event;

use App\Contract\AsynchronousEventInterface;
use Ramsey\Uuid\UuidInterface;

class VideoAddedEvent implements AsynchronousEventInterface
{
    public function __construct(
        private readonly UuidInterface $measurementUuid,
    ) {}

    public function getMeasurementUuid(): UuidInterface
    {
        return $this->measurementUuid;
    }
}