<?php

namespace App\Factory;

use App\Response\ErrorResponse;
use App\ValueObject\ValidationError;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorResponseFactory
{
    public function build(ConstraintViolationListInterface $violations): ErrorResponse
    {
        # TODO: add unit tests
        $response = new ErrorResponse();

        /**
         * @var ConstraintViolationInterface $violation
         */
        foreach ($violations as $violation) {
            $response->addError(new ValidationError(
                $violation->getPropertyPath(),
                $violation->getMessage(),
                $violation->getCode(),
            ));
        }

        return $response;
    }

    private function extractPropertyNameFromPath(string $propertyPath): string
    {
        $propertyPathElements = \explode('.', $propertyPath);
        return \end($propertyPathElements);
    }
}
