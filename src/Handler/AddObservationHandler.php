<?php

namespace App\Handler;

use App\Command\AddObservationCommand;
use App\Contract\ObservationRepositoryInterface;
use App\Entity\Observation;
use App\Entity\User;

class AddObservationHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository
    ) {}

    public function __invoke(AddObservationCommand $command, User $user): void
    {
        $this->observationRepository->add(
            new Observation(
                $user,
                $command->uuid,
                $command->name,
            ),
            true
        );
    }
}
