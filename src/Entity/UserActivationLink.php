<?php

namespace App\Entity;

use App\Contract\UserInterface;
use App\Repository\Entity\UserActivationLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: UserActivationLinkRepository::class)]
class UserActivationLink
{
    private const TIME_TO_EXPIRE_IN_MINUTES = 60;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    private readonly UuidInterface $id;

    #[ORM\Column(type: 'string')]
    private readonly string $url;

    #[ORM\Column(type: 'boolean')]
    private bool $wasSend = false;

    #[ORM\Column(type: 'boolean')]
    private bool $wasUsed = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private readonly \DateTimeImmutable $expirationDate;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private readonly UserInterface $user;

    public function __construct(string $url, UserInterface $user)
    {
        $this->url = $url;
        $this->user = $user;
        $this->expirationDate = new \DateTimeImmutable("+" . self::TIME_TO_EXPIRE_IN_MINUTES . " minutes");
    }

    public function hasExpired(): bool
    {
        return $this->expirationDate < new \DateTimeImmutable();
    }

    public function wasSend(): bool
    {
        return $this->wasSend;
    }

    public function markAsSent(): self
    {
        $this->wasSend = true;

        return $this;
    }

    public function wasUsed(): bool
    {
        return $this->wasUsed;
    }

    public function markAsUsed(): self
    {
        $this->wasUsed = true;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function __toString(): string
    {
        return \sprintf($this->url, $this->id);
    }
}
