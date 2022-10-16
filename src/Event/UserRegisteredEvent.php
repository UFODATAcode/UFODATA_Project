<?php

namespace App\Event;

use App\Contract\UserInterface;

class UserRegisteredEvent
{
    public function __construct(
        private readonly UserInterface $user,
    ) {}

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
