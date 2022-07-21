<?php

namespace App\Handler;

use App\Command\AddMeasurementCommand;
use App\Contract\MeasurementRepositoryInterface;
use App\Contract\ObservationRepositoryInterface;
use App\Entity\RadioFrequencySpectrum;
use App\Enum\MeasurementType;

class AddMeasurementHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository,
        private readonly MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function __invoke(AddMeasurementCommand $command): void
    {
        $this->measurementRepository->add(
            match ($command->measurementType) {
                MeasurementType::RadioFrequencySpectrum => new RadioFrequencySpectrum(
                    $command->uuid,
                    $this->observationRepository->findOneByUuid($command->observationUuid),
                    $command->measurement,
                ),
            },
            true
        );
    }
}
