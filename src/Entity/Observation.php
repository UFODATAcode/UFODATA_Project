<?php

namespace App\Entity;

use App\Repository\ObservationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ObservationRepository::class)]
class Observation extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private readonly User $provider;

    #[ORM\Column(type: 'string', length: 255)]
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
