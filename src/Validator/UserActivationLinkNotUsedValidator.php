<?php

namespace App\Validator;

use App\Contract\UserActivationLinkRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UnexpectedValueException;

class UserActivationLinkNotUsedValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserActivationLinkRepositoryInterface $userActivationLinkRepository,
    ) {}

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof UserActivationLinkNotUsed) {
            throw new UnexpectedTypeException($constraint, UserActivationLinkNotUsed::class);
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

        $link = $this->userActivationLinkRepository->find(Uuid::fromString($value));

        if (null !== $link && $link->wasUsed()) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ activationLink }}', $value)
                ->setCode($constraint->code)
                ->addViolation();
        }
    }
}