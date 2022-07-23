<?php

namespace App\Contract;

interface UserInterface extends ResourceInterface
{
    public function getName(): string;
}
