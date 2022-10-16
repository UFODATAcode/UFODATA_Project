<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserActivationLinkNotExpired extends Constraint
{
    public string $message = 'User activation link "{{ activationLink }}" has expired.';
    public string $code = '9175143c-61f1-47bc-99e7-f311f13d1295';
}