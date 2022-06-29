<?php

namespace App\Entity;

use App\Contract\ResourceInterface;
use App\Repository\ObservationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ObservationRepository::class)]
class Observation extends AbstractEntity implements ResourceInterface
{
    public const NAME_MAX_LENGTH = 64;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private readonly User $provider;

    #[ORM\Column(type: 'string', length: self::NAME_MAX_LENGTH)]
    private string $name;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetimetz_immutable')]
    private readonly DateTimeImmutable $providedAt;

    public function __construct(
        User $provider,
        UuidInterface $uuid,
        string $name
    ) {
        $this->provider = $provider;
        $this->uuid = $uuid;
        $this->name = $name;
        $this->providedAt = new DateTimeImmutable();
    }

    public function getProvider(): User
    {
        return $this->provider;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProvidedAt(): DateTimeImmutable
    {
        return $this->providedAt;
    }
}
