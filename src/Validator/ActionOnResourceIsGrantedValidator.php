<?php

namespace App\Validator;

use App\Contract\ResourceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use UnexpectedValueException;

class ActionOnResourceIsGrantedValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly Security $security,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        # TODO: add unit test

        if (!$constraint instanceof ActionOnResourceIsGranted) {
            throw new UnexpectedTypeException($constraint, ActionOnResourceIsGranted::class);
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
            return;
        }

        if (!$result instanceof ResourceInterface) {
            throw new UnexpectedValueException(\get_class($result), ResourceInterface::class);
        }

        $userIsNotResourceOwner = $result->getProvider() !== $this->security->getUser();
        $userIsNotAdmin = !$this->authorizationChecker->isGranted('ROLE_ADMIN');

        if ($userIsNotResourceOwner && $userIsNotAdmin) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode($constraint->code)
                ->addViolation();
        }
    }
}
