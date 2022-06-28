<?php

namespace App\Contract;

use App\Entity\Observation;
use App\ValueObject\Pagination;
use Ramsey\Uuid\UuidInterface;

interface ObservationRepositoryInterface
{
    public function add(Observation $entity, bool $flush = false): void;
    public function remove(Observation $entity, bool $flush = false): void;
    /**
     * @return Observation[]
     */
    public function findAllForList(Pagination $pagination): array;
    public function findOneByUuid(UuidInterface $uuid): ?Observation;
    public function update(): void;
}
