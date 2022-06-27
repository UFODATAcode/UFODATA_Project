<?php

namespace App\EventListener;

use App\Exception\CommandValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = match (\get_class($exception)) {
            CommandValidationException::class => new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST),
            default => null,
        };

        if (null !== $response) {
            $event->setResponse($response);
        }
    }
}
