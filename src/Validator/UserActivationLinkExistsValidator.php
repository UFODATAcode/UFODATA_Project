<?php

namespace App\Validator;

use App\Contract\UserActivationLinkRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UnexpectedValueException;

class UserActivationLinkExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserActivationLinkRepositoryInterface $userActivationLinkRepository,
    ) {}

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof UserActivationLinkExists) {
            throw new UnexpectedTypeException($constraint, UserActivationLinkExists::class);
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

        if (null === $link) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ activationLink }}', $value)
                ->setCode($constraint->code)
                ->addViolation();
        }
    }
}