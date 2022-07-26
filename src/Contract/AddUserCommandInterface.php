<?php

namespace App\Contract;

interface AddUserCommandInterface extends CommandInterface
{
    public function getEmail(): string;
    public function getName(): string;
    public function getPassword(): string;
    /** @return string[] */
    public function getRoles(): array;
    public function getActive(): bool;
}
