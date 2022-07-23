<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ActionOnResourceIsGranted extends Constraint
{
    public string $message = 'User is not resource owner.';
    public string $entityClassName;
    public string $code = '4bcf7afc-662b-438c-9a0a-6822dd608b75';

    #[HasNamedArguments]
    public function __construct(string $entityClassName, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entityClassName = $entityClassName;
    }
}
