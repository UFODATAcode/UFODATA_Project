<?php

namespace App\Contract;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method User[]    findAllForList(PaginationInterface $pagination)
 * @method User|null findOneByUuid(UuidInterface $uuid)
 * @method User|null add(User $User, bool $flush = false)
 * @method User|null remove(User $User, bool $flush = false)
 */
interface UserRepositoryInterface extends ResourceRepositoryInterface
{
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
}
