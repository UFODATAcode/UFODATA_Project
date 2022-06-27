<?php

namespace App\ArgumentResolver;

use App\Contract\CommandInterface;
use App\Exception\CommandValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {}

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return \in_array(CommandInterface::class, \class_implements($argument->getType()));
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        #TODO: check content-type
        $command = $this->serializer->deserialize($request->getContent(), $argument->getType(), $request->getContentType());

        $violations = $this->validator->validate($command);

        if ($violations->count() > 0) {
            # TODO: prepare unified error message template
            throw new CommandValidationException((string) $violations);
        }

        yield $command;
    }
}
