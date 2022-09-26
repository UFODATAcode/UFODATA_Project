<?php

namespace App\Command;

use App\Contract\DeleteUserCommandInterface;
use App\Contract\UserInterface;
use App\Entity\User;
use App\Validator\ActionOnResourceIsGranted;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteUserCommand implements DeleteUserCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: User::class)]
    #[ActionOnResourceIsGranted(entityClassName: User::class)]
    public UuidInterface $uuid;

    #[Ignore]
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
