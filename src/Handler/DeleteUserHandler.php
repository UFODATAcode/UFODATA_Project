<?php

namespace App\Handler;

use App\Contract\DeleteUserCommandInterface;
use App\Contract\UserRepositoryInterface;

class DeleteUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(DeleteUserCommandInterface $command): void
    {
        $observation = $this->userRepository->findOneByUuid($command->getUuid());
        $this->userRepository->remove($observation);
    }
}
