<?php

namespace App\Repository\Entity;

use App\Contract\ObservationRepositoryInterface;
use App\Entity\Observation;
use Doctrine\Persistence\ManagerRegistry;

class ObservationRepository extends AbstractResourceRepository implements ObservationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Observation::class);
    }
}
