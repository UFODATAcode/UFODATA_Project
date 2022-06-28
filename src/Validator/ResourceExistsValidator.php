<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UnexpectedValueException;

class ResourceExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        # TODO: add unit test

        if (!$constraint instanceof ResourceExists) {
            throw new UnexpectedTypeException($constraint, ResourceExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!Uuid::isValid($value)) {
            return;
        }

        $result = $this->manager->getRepository($constraint->entityClassName)->findOneBy(['uuid' => $value]);

        if (null === $result) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ givenUuid }}', $value)
                ->setCode($constraint->code)
                ->addViolation();
        }
    }
}
