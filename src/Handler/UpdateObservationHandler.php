<?php

namespace App\Handler;

use App\Contract\ObservationRepositoryInterface;
use App\Contract\UpdateObservationCommandInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateObservationHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository,
    ) {}

    public function __invoke(UpdateObservationCommandInterface $command): void
    {
        $observation = $this->observationRepository->findOneByUuid($command->getUuid());
        $observation->setName($command->getName());

        $this->observationRepository->update();
    }
}
