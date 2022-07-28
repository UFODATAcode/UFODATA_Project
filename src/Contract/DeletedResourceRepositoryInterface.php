<?php

namespace App\Contract;

use App\Entity\DeletedResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<DeletedResource>
 *
 * @method DeletedResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeletedResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeletedResource[]    findAll()
 * @method DeletedResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method DeletedResource[]    findAllForList(PaginationInterface $pagination)
 * @method DeletedResource|null findOneByUuid(UuidInterface $uuid)
 * @method DeletedResource|null add(DeletedResource $observation, bool $flush = false)
 * @method DeletedResource|null remove(DeletedResource $observation, bool $flush = false)
 */
interface DeletedResourceRepositoryInterface extends ResourceRepositoryInterface
{

}
