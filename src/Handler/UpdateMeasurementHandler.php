<?php

namespace App\Handler;

use App\Contract\MeasurementRepositoryInterface;
use App\Contract\UpdateMeasurementCommandInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateMeasurementHandler
{
    public function __construct(
        private readonly MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function __invoke(UpdateMeasurementCommandInterface $command): void
    {
        $measurement = $this->measurementRepository->findOneByUuid($command->getUuid());
        $measurement->setName($command->getName());
        $this->measurementRepository->update();
    }
}
