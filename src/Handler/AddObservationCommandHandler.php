<?php

namespace App\Handler;

use App\Command\AddObservationCommand;
use App\Entity\Observation;
use App\Entity\User;
use App\Repository\ObservationRepository;
use Ramsey\Uuid\Uuid;

class AddObservationCommandHandler
{
    public function __construct(
        private readonly ObservationRepository $observationRepository
    ) {}

    public function __invoke(AddObservationCommand $command, User $user): void
    {
        $this->observationRepository->add(
            new Observation(
                $user,
                Uuid::fromString($command->uuid),
                $command->name
            ),
            true
        );
    }
}
