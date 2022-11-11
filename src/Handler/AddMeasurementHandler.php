<?php

namespace App\Handler;

use App\Contract\AddMeasurementCommandInterface;
use App\Contract\MeasurementRepositoryInterface;
use App\Contract\ObservationRepositoryInterface;
use App\Entity\MissionControlAdsBFlightTracking;
use App\Entity\MissionControlData;
use App\Entity\MissionControlWeather;
use App\Entity\RadioFrequencySpectrum;
use App\Enum\MeasurementType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddMeasurementHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository,
        private readonly MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function __invoke(AddMeasurementCommandInterface $command): void
    {
        $measurementClassName = match ($command->getMeasurementType()) {
            MeasurementType::RadioFrequencySpectrum => RadioFrequencySpectrum::class,
            MeasurementType::MissionControlData => MissionControlData::class,
            MeasurementType::MissionControlAdsBFlightTracking => MissionControlAdsBFlightTracking::class,
            MeasurementType::MissionControlWeather => MissionControlWeather::class,
        };

        $this->measurementRepository->add(
            new $measurementClassName(
                $command->getUuid(),
                $this->observationRepository->findOneByUuid($command->getObservationUuid()),
                $command->getProvider(),
                $command->getMeasurement(),
            ),
            true
        );
    }
}
