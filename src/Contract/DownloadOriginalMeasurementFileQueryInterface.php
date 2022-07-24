<?php

namespace App\Contract;

use Ramsey\Uuid\UuidInterface;

interface DownloadOriginalMeasurementFileQueryInterface extends QueryInterface
{
    public function getUuid(): UuidInterface;
}
