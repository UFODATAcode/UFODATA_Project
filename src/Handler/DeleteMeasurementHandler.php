<?php

namespace App\Handler;

use App\Contract\DeleteMeasurementCommandInterface;
use App\Contract\MeasurementRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeleteMeasurementHandler
{
    public function __construct(
        private readonly MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function __invoke(DeleteMeasurementCommandInterface $command): void
    {
        $measurement = $this->measurementRepository->findOneByUuid($command->getUuid());
        $this->measurementRepository->remove($measurement);
    }
}
