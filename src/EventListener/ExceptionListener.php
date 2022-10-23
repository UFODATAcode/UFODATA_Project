<?php

namespace App\EventListener;

use App\Exception\ActionDeniedException;
use App\Exception\ValidationException;
use App\Factory\ErrorResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
                \json_encode($this->errorResponseFactory->buildFromViolationList($exception->getViolations())),
                Response::HTTP_BAD_REQUEST,
            ),
            ActionDeniedException::class => new Response(
                \json_encode($this->errorResponseFactory->buildFromActionDeniedException($exception)),
                Response::HTTP_FORBIDDEN,
            ),
            AccessDeniedHttpException::class => new Response(null, Response::HTTP_FORBIDDEN),
            default => null,
        };

        if (null !== $response) {
            $event->setResponse($response);
            $event->getResponse()->headers->add(['Content-Type' => 'application/json']);
        }
    }
}
