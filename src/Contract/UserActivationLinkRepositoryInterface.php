<?php

namespace App\Contract;

use App\Entity\UserActivationLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<UserActivationLink>
 *
 * @method UserActivationLink|null find(UuidInterface $uuid)
 * @method UserActivationLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserActivationLink[]    findAll()
 * @method UserActivationLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface UserActivationLinkRepositoryInterface
{
    public function add(UserActivationLink $entity, bool $flush = false): void;
    public function update(UserActivationLink $entity): void;
}