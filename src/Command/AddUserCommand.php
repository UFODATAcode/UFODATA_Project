<?php

namespace App\Command;

use App\Contract\AddUserCommandInterface;
use App\Contract\UserInterface;
use App\Entity\User;
use App\Validator\ResourceNotExists;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class AddUserCommand implements AddUserCommandInterface
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

    #[Assert\Type('array')]
    #[Assert\All(constraints: [
        new Assert\Choice(User::ROLES),
    ])]
    #[OA\Property(type: 'string', enum: User::ROLES)]
    public array $roles;

    #[Assert\NotNull]
    #[Assert\Type('bool')]
    public bool $active;

    #[Ignore]
    public UserInterface $provider;

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

    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
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
