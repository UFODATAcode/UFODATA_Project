<?php

namespace App\Contract;

use Ramsey\Uuid\UuidInterface;

interface GetUserQueryInterface extends QueryInterface
{
    public function getUserUuid(): UuidInterface;
}
