<?php

namespace App\Contract;

interface ChangeUserPasswordCommandInterface extends CommandInterface
{
    public function getOldPassword(): string;
    public function getNewPassword(): string;
}