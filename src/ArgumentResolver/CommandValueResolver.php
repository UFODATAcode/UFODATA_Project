<?php

namespace App\ArgumentResolver;

use App\Contract\CommandInterface;
use App\Exception\ValidationException;
use Ramsey\Uuid\Uuid;
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

        if (null !== ($uuid = $request->get('uuid'))) {
            $command->uuid = $uuid;
        }

        $violations = $this->validator->validate($command);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $command->uuid = Uuid::fromString($command->uuid);

        yield $command;
    }
}
