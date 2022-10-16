<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserActivationLinkNotUsed extends Constraint
{
    public string $message = 'User activation link "{{ activationLink }}" was already used.';
    public string $code = '892924a5-7efc-4db3-8233-90cdc03805be';
}