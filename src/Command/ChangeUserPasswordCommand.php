<?php

namespace App\Command;

use App\Contract\ChangeUserPasswordCommandInterface;
use App\Contract\SynchronousCommandInterface;
use App\Contract\UserInterface;
use App\Entity\User;
use App\Validator\MeetsPasswordPolicy;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangeUserPasswordCommand implements ChangeUserPasswordCommandInterface, SynchronousCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: User::class)]
    public UuidInterface $uuid;

    #[SecurityAssert\UserPassword]
    public string $oldPassword;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\NotCompromisedPassword]
    #[MeetsPasswordPolicy]
    public string $newPassword;

    #[Ignore]
    public UserInterface $provider;

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }
}