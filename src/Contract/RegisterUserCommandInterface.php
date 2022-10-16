<?php

namespace App\Contract;

interface RegisterUserCommandInterface extends CommandInterface
{
    public function getEmail(): string;
    public function getName(): string;
    public function getPassword(): string;
}
