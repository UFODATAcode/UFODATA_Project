<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class MeetsPasswordPolicy extends Constraint
{
    public string $message = 'Password must at least: be 8 characters length, contains one upper case, contains one lower case, contains one special character and contains one digit.';
    public string $code = 'a8ff120d-830b-4ea3-ac28-d192f7273e9d';
}