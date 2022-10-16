<?php

namespace App\Repository\Entity;

use App\Contract\UserActivationLinkRepositoryInterface;
use App\Entity\UserActivationLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserActivationLinkRepository extends ServiceEntityRepository implements UserActivationLinkRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserActivationLink::class);
    }

    public function add(UserActivationLink $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function update(UserActivationLink $entity): void
    {
        $this->getEntityManager()->flush();
    }
}
