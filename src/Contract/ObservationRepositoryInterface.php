<?php

namespace App\Contract;

use App\Entity\Observation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<Observation>
 *
 * @method Observation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Observation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Observation[]    findAll()
 * @method Observation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Observation[]    findAllForList(PaginationInterface $pagination)
 * @method Observation|null findOneByUuid(UuidInterface $uuid)
 * @method Observation|null add(Observation $observation, bool $flush = false)
 * @method Observation|null remove(Observation $observation, bool $flush = false)
 */
interface ObservationRepositoryInterface extends ResourceRepositoryInterface
{

}
