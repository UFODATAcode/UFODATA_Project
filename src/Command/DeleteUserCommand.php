<?php

namespace App\Command;

use App\Contract\DeleteUserCommandInterface;
use App\Contract\SynchronousCommandInterface;
use App\Contract\UserInterface;
use App\Entity\User;
use App\Validator\ActionOnResourceIsGranted;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteUserCommand implements DeleteUserCommandInterface, SynchronousCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: User::class)]
    #[ActionOnResourceIsGranted(entityClassName: User::class)]
    public UuidInterface $uuid;

    public UserInterface $provider;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }
}
