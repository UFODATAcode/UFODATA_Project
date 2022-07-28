<?php

namespace App\Entity;

use App\Repository\Entity\DeletedResourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: DeletedResourceRepository::class)]
class DeletedResource extends AbstractEntity
{
    #[ORM\Column(type: 'datetimetz_immutable')]
    private \DateTimeImmutable $deletedAt;

    #[ORM\Column(type: 'string', length: 64)]
    private string $resourceClass;

    #[ORM\Column(type: 'json')]
    private array $data = [];

    #[ORM\Column(type: 'integer')]
    private int $originalInternalId;

    public function __construct(
        UuidInterface $uuid,
        string $resourceClass,
        array $data,
        int $originalInternalId,
    ) {
        parent::__construct($uuid);
        $this->deletedAt = new \DateTimeImmutable();
        $this->resourceClass = $resourceClass;
        $this->data = $data;
        $this->originalInternalId = $originalInternalId;
    }

    public function getDeletedAt(): \DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getOriginalInternalId(): int
    {
        return $this->originalInternalId;
    }
}
