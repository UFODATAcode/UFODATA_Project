<?php

namespace App\Validator;

use App\Security\PasswordPolicy;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UnexpectedValueException;

class MeetsPasswordPolicyValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof MeetsPasswordPolicy) {
            throw new UnexpectedTypeException($constraint, MeetsPasswordPolicy::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (PasswordPolicy::isMet($value)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->setCode($constraint->code)
            ->addViolation();
    }
}