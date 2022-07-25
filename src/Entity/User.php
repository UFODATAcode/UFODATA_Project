<?php

namespace App\Entity;

use App\Contract\UserInterface;
use App\Repository\Entity\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User extends AbstractEntity implements SecurityUserInterface, PasswordAuthenticatedUserInterface, UserInterface
{
    public const EMAIL_MAX_LENGTH = 180;
    public const NAME_MAX_LENGTH = 32;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: self::EMAIL_MAX_LENGTH, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: self::NAME_MAX_LENGTH, unique: true)]
    private string $name;

    public function __construct(
        string $email,
        UuidInterface $uuid,
        string $name,
    ) {
        parent::__construct($uuid);

        $this->email = $email;
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see SecurityUserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see SecurityUserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getProvider(): UserInterface
    {
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
