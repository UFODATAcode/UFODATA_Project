<?php

namespace App\Handler;

use App\Contract\AddObservationCommandInterface;
use App\Contract\ObservationRepositoryInterface;
use App\Entity\Observation;

class AddObservationHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository
    ) {}

    public function __invoke(AddObservationCommandInterface $command): void
    {
        $this->observationRepository->add(
            new Observation(
                $command->getProvider(),
                $command->getUuid(),
                $command->getName(),
            ),
            true
        );
    }
}
