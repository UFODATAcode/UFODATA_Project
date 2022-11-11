<?php

namespace App\Handler;

use App\Contract\AddUserCommandInterface;
use App\Contract\UserRepositoryInterface;
use App\Entity\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class AddUserHandler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(AddUserCommandInterface $command): void
    {
        $newUser = new User(
            $command->getEmail(),
            $command->getUuid(),
            $command->getName(),
            $command->getActive(),
        );
        $newUser
            ->setRoles($command->getRoles())
            ->setPassword($this->passwordHasher->hashPassword($newUser, $command->getPassword()));

        $this->userRepository->add($newUser);
    }
}
