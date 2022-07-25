<?php

namespace App\Query;

use App\Contract\GetUserQueryInterface;
use App\Entity\User;
use App\Validator\ResourceExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetUserQuery implements GetUserQueryInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: User::class)]
    public UuidInterface $uuid;

    public function getUserUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
