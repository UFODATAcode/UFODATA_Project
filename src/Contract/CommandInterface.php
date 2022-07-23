<?php

namespace App\Contract;

use Ramsey\Uuid\UuidInterface;

interface CommandInterface
{
    public function getUuid(): UuidInterface;
    public function getProvider(): UserInterface;
}
