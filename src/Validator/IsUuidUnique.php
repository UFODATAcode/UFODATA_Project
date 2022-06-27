<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsUuidUnique extends Constraint
{
    public string $message = 'A resource with "{{ givenUuid }}" UUID already exists.';
    public string $entityClassName;

    #[HasNamedArguments]
    public function __construct(string $entityClassName, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entityClassName = $entityClassName;
    }
}
