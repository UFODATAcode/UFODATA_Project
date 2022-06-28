<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ResourceExists extends Constraint
{
    public string $message = 'A resource with "{{ givenUuid }}" UUID does not exist.';
    public string $entityClassName;
    public string $code = 'ab1db128-e844-4dbd-9988-e0758f26a5af';

    #[HasNamedArguments]
    public function __construct(string $entityClassName, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entityClassName = $entityClassName;
    }
}
