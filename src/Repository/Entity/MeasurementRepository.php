<?php

namespace App\Repository\Entity;

use App\Contract\MeasurementRepositoryInterface;
use App\Entity\Measurement;
use Doctrine\Persistence\ManagerRegistry;

class MeasurementRepository extends AbstractResourceRepository implements MeasurementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }
}
