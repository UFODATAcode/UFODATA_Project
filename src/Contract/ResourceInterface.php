<?php

namespace App\Contract;

interface ResourceInterface
{
    public function getUuid();
    public function getProvider(): UserInterface;
}
