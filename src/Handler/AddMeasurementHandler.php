<?php

namespace App\Handler;

use App\Contract\AddMeasurementCommandInterface;
use App\Contract\MeasurementRepositoryInterface;
use App\Contract\ObservationRepositoryInterface;
use App\Entity\MissionControlAdsBFlightTracking;
use App\Entity\MissionControlData;
use App\Entity\MissionControlWeather;
use App\Entity\RadioFrequencySpectrum;
use App\Entity\Video;
use App\Enum\MeasurementType;
use App\Event\VideoAddedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class AddMeasurementHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository,
        private readonly MeasurementRepositoryInterface $measurementRepository,
        private readonly MessageBusInterface $messageBus,
    ) {}

    public function __invoke(AddMeasurementCommandInterface $command): void
    {
        $measurementClassName = match ($command->getType()) {
            MeasurementType::RadioFrequencySpectrum => RadioFrequencySpectrum::class,
            MeasurementType::MissionControlData => MissionControlData::class,
            MeasurementType::MissionControlAdsBFlightTracking => MissionControlAdsBFlightTracking::class,
            MeasurementType::MissionControlWeather => MissionControlWeather::class,
            MeasurementType::Video => Video::class,
        };

        $this->measurementRepository->add(
            new $measurementClassName(
                $command->getUuid(),
                $this->observationRepository->findOneByUuid($command->getObservationUuid()),
                $command->getProvider(),
                $command->getFile(),
            ),
            true
        );

        if ($measurementClassName === Video::class) {
            $this->messageBus->dispatch(new VideoAddedEvent($command->getUuid()));
        }
    }
}
