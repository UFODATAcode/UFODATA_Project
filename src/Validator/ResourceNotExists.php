<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ResourceNotExists extends Constraint
{
    public string $message = 'A resource with "{{ givenUuid }}" UUID already exists.';
    public string $entityClassName;
    public string $code = '74ae47e1-6d43-4dfc-831e-7db274ff494b';

    #[HasNamedArguments]
    public function __construct(string $entityClassName, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->entityClassName = $entityClassName;
    }
}
