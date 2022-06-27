<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UnexpectedValueException;

class IsUuidUniqueValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        # TODO: add unit test

        if (!$constraint instanceof IsUuidUnique) {
            throw new UnexpectedTypeException($constraint, IsUuidUnique::class);
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

        if (null !== $result) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ givenUuid }}', $value)
                ->addViolation();
        }
    }
}
