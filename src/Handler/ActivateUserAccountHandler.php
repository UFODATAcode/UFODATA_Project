<?php

namespace App\Handler;

use App\Contract\ActivateUserAccountCommandInterface;
use App\Contract\UserActivationLinkRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ActivateUserAccountHandler
{
    public function __construct(
        private readonly UserActivationLinkRepositoryInterface $userActivationLinkRepository,
    ) {}

    public function __invoke(ActivateUserAccountCommandInterface $command): void
    {
        $link = $this->userActivationLinkRepository->find($command->getUuid());
        $link->getUser()->activate();
        $link->markAsUsed();
        $this->userActivationLinkRepository->update($link);
    }
}