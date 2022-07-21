<?php

namespace App\Handler;

use App\Command\UpdateMeasurementCommand;
use App\Contract\MeasurementRepositoryInterface;

class UpdateMeasurementHandler
{
    public function __construct(
        private readonly MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function __invoke(UpdateMeasurementCommand $command): void
    {
        $measurement = $this->measurementRepository->findOneByUuid($command->uuid);
        $measurement->setName($command->name);
        $this->measurementRepository->update();
    }
}
