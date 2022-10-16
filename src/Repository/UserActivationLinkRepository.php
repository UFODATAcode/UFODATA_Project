<?php

namespace App\Repository;

use App\Entity\UserActivationLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserActivationLink>
 *
 * @method UserActivationLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserActivationLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserActivationLink[]    findAll()
 * @method UserActivationLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserActivationLinkRepository extends ServiceEntityRepository
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

    public function remove(UserActivationLink $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function update(UserActivationLink $entity): void
    {
        $this->getEntityManager()->flush();
    }
}
