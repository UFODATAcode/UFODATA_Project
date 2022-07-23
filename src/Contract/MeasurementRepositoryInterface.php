<?php

namespace App\Contract;

use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<Measurement>
 *
 * @method Measurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Measurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Measurement[]    findAll()
 * @method Measurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Measurement[]    findAllForList(PaginationInterface $pagination)
 * @method Measurement|null findOneByUuid(UuidInterface $uuid)
 * @method Measurement|null add(Measurement $observation, bool $flush = false)
 * @method Measurement|null remove(Measurement $observation, bool $flush = false)
 */
interface MeasurementRepositoryInterface extends ResourceRepositoryInterface
{

}
