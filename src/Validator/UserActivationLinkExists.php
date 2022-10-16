<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserActivationLinkExists extends Constraint
{
    public string $message = 'Activation link "{{ activationLink }}" does not exist.';
    public string $code = '9b9e8075-0ecf-4c0e-8c45-372dcb0484b0';
}
