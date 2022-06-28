<?php

namespace App\Handler;

use App\Command\DeleteObservationCommand;
use App\Entity\User;
use App\Exception\UserIsNotResourceOwnerException;
use App\Repository\ObservationRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DeleteObservationCommandHandler
{
    public function __construct(
        private readonly ObservationRepository $observationRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker
    ) {}

    public function __invoke(DeleteObservationCommand $command, User $user): void
    {
        $observation = $this->observationRepository->findOneByUuid($command->uuid);
        $userIsNotResourceOwner = $observation->getProvider() !== $user;
        $userIsNotAdmin = !$this->authorizationChecker->isGranted('ROLE_ADMIN');

        if ($userIsNotResourceOwner && $userIsNotAdmin) {
            throw new UserIsNotResourceOwnerException();
        }

        $this->observationRepository->remove($observation);
    }
}
