<?php

namespace App\Repository\Entity;

use App\Contract\PaginationInterface;
use App\Contract\ResourceInterface;
use App\Contract\ResourceRepositoryInterface;
use App\Entity\DeletedResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractResourceRepository extends ServiceEntityRepository implements ResourceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function add(ResourceInterface $resource, bool $flush = false): void
    {
        $this->getEntityManager()->persist($resource);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ResourceInterface $resource, bool $flush = false): void
    {
        $manager = $this->getEntityManager();

        $manager->beginTransaction();

        try {
            $manager->persist(new DeletedResource(
                $resource->getUuid(),
                \get_class($resource),
                $resource->getData(),
                $resource->getId(),
            ));
            $manager->remove($resource);

            if ($flush) {
                $manager->flush();
            }
        } catch (\Throwable $exception) {
            $manager->rollback();
            throw $exception;
        }

        $manager->commit();
    }

    public function update(): void
    {
        $this->getEntityManager()->flush();
    }

    public function findAllForList(PaginationInterface $pagination): array
    {
        $qb = $this->createQueryBuilder('o');

        $result = $qb
            ->setMaxResults($pagination->getLimit())
            ->setFirstResult($pagination->getSqlOffset())
            ->getQuery()
            ->getResult();

        $numberOfRecords = $this
            ->createQueryBuilder('o')
            ->select($qb->expr()->count('o'))
            ->getQuery()
            ->getSingleScalarResult();

        $pagination->setNumberOfPages(\ceil($numberOfRecords / $pagination->getLimit()));

        return $result;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByUuid(UuidInterface $uuid): ?ResourceInterface
    {
        $qb = $this->createQueryBuilder('o');

        return $qb
            ->where($qb->expr()->eq('o.uuid', ':uuid'))
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
