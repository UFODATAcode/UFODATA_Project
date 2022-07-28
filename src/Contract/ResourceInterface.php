<?php

namespace App\Contract;

interface ResourceInterface
{
    public function getUuid();
    public function getProvider(): UserInterface;
    public function getData(): array;
    public function getId(): ?int;
}
