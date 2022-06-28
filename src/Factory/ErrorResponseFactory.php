<?php

namespace App\Factory;

use App\Exception\ActionDeniedException;
use App\Response\ErrorResponse;
use App\ValueObject\Error;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorResponseFactory
{
    public function buildFromViolationList(ConstraintViolationListInterface $violations): ErrorResponse
    {
        # TODO: add unit tests
        $response = new ErrorResponse();

        /**
         * @var ConstraintViolationInterface $violation
         */
        foreach ($violations as $violation) {
            $response->addError(new Error(
                $violation->getPropertyPath(),
                $violation->getMessage(),
                $violation->getCode(),
            ));
        }

        return $response;
    }

    public function buildFromActionDeniedException(ActionDeniedException $exception): ErrorResponse
    {
        $response = new ErrorResponse();
        $response->addError(new Error('uuid', $exception->getMessage(), $exception->getErrorCode()));
        return $response;
    }

    private function extractPropertyNameFromPath(string $propertyPath): string
    {
        $propertyPathElements = \explode('.', $propertyPath);
        return \end($propertyPathElements);
    }
}
