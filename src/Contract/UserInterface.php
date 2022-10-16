<?php

namespace App\Contract;

interface UserInterface extends ResourceInterface
{
    public function getName(): string;
    public function isActive(): bool;
    public function getEmail(): string;
    public function activate(): self;
}
