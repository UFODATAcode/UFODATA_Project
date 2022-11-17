<?php

namespace App\Event;

use App\Contract\AsynchronousEventInterface;
use Ramsey\Uuid\UuidInterface;

class UserRegisteredEvent implements AsynchronousEventInterface
{
    public function __construct(
        private readonly UuidInterface $userUuid,
    ) {}

    public function getUserUuid(): UuidInterface
    {
        return $this->userUuid;
    }
}
