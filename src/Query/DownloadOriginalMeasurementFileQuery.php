<?php

namespace App\Query;

use App\Contract\DownloadOriginalMeasurementFileQueryInterface;
use App\Entity\Measurement;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DownloadOriginalMeasurementFileQuery implements DownloadOriginalMeasurementFileQueryInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Measurement::class)]
    public UuidInterface $uuid;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
