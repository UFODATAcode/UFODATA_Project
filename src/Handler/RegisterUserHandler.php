<?php

namespace App\Handler;

use App\Contract\RegisterUserCommandInterface;
use App\Contract\UserRepositoryInterface;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class RegisterUserHandler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepositoryInterface $userRepository,
        private readonly MessageBusInterface $bus,
    ) {}

    public function __invoke(RegisterUserCommandInterface $command): void
    {
        $newUser = new User(
            $command->getEmail(),
            $command->getUuid(),
            $command->getName(),
        );
        $newUser
            ->setPassword($this->passwordHasher->hashPassword($newUser, $command->getPassword()));

        $this->userRepository->add($newUser, true);
        $this->bus->dispatch(new UserRegisteredEvent($newUser->getUuid()));
    }
}
