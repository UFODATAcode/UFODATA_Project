<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use App\Factory\ErrorResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __construct(
        private readonly ErrorResponseFactory $errorResponseFactory
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = match (\get_class($exception)) {
            ValidationException::class => new Response(
                # TODO: use serializer and take expected response format from header
                \json_encode($this->errorResponseFactory->build($exception->getViolations())),
                Response::HTTP_BAD_REQUEST
            ),
            default => null,
        };

        if (null !== $response) {
            $event->setResponse($response);
        }
    }
}
