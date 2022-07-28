<?php

namespace App\Repository\Entity;

use App\Contract\DeletedResourceRepositoryInterface;
use App\Entity\DeletedResource;
use Doctrine\Persistence\ManagerRegistry;

class DeletedResourceRepository extends AbstractResourceRepository implements DeletedResourceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeletedResource::class);
    }
}
