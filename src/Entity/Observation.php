<?php

namespace App\Entity;

use App\Repository\ObservationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ObservationRepository::class)]
class Observation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private readonly ?int $id;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime_immutable')]
    private readonly DateTimeImmutable $providedAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        private readonly User $provider,

        #[ORM\Column(type: 'string', length: 255)]
        private string $name,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
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
