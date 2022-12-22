<?php

namespace App\Contract;

use App\Enum\MeasurementType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface AddMeasurementCommandInterface extends CommandInterface
{
    public function getObservationUuid(): UuidInterface;
    public function getType(): MeasurementType;
    public function getFile(): UploadedFile;
    public function getName(): ?string;
}
