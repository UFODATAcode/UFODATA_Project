<?php

namespace App\Handler;

use App\Contract\ChangeUserPasswordCommandInterface;
use App\Contract\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class ChangeUserPasswordHandler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(ChangeUserPasswordCommandInterface $command): void
    {
        $user = $this->userRepository->findOneByUuid($command->getUuid());
        $this->userRepository->upgradePassword($user, $this->passwordHasher->hashPassword($user, $command->getNewPassword()));
    }
}