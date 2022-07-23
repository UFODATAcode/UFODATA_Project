<?php

namespace App\Repository\Entity;

use App\Contract\MeasurementRepositoryInterface;
use App\Contract\PaginationInterface;
use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class MeasurementRepository extends AbstractResourceRepository implements MeasurementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }
}
