<?php

namespace App\Contract;

use Ramsey\Uuid\UuidInterface;

interface ResourceRepositoryInterface
{
    public function add(ResourceInterface $resource, bool $flush = false): void;
    public function remove(ResourceInterface $resource, bool $flush = false): void;
    public function update(): void;
    public function findAllForList(PaginationInterface $pagination): array;
    public function findOneByUuid(UuidInterface $uuid): ?ResourceInterface;
}
