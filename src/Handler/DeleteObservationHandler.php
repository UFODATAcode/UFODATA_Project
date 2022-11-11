<?php

namespace App\Handler;

use App\Contract\DeleteObservationCommandInterface;
use App\Contract\ObservationRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeleteObservationHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository,
    ) {}

    public function __invoke(DeleteObservationCommandInterface $command): void
    {
        $observation = $this->observationRepository->findOneByUuid($command->getUuid());
        $this->observationRepository->remove($observation);
    }
}
