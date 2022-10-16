<?php

namespace App\Command;

use App\Contract\AnonymousUserInterface;
use App\Contract\RegisterUserCommandInterface;
use App\Contract\UserInterface;
use App\Entity\User;
use App\Validator\ResourceNotExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserCommand implements RegisterUserCommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceNotExists(entityClassName: User::class)]
    public UuidInterface $uuid;

    //todo: check uniqueness
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: User::EMAIL_MAX_LENGTH)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: User::NAME_MAX_LENGTH)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\NotCompromisedPassword]
    public string $password;

    //todo: get rid of anonymous users in anonymous requests?
    #[Ignore]
    public AnonymousUserInterface $provider;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
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
