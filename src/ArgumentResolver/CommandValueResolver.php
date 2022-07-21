<?php

namespace App\ArgumentResolver;

use App\Contract\CommandInterface;
use App\Contract\FileUploadInterface;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly DenormalizerInterface $denormalizer,
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
        $commandClass = $argument->getType();
        #TODO: check content-type. What with other formats?
        $decodedContent = \json_decode($request->getContent(), true);

        if (null !== ($uuid = $request->get('uuid'))) {
            $decodedContent['uuid'] = $uuid;
        }

        if (\in_array(FileUploadInterface::class, \class_implements($commandClass))) {
            /** @var FileUploadInterface $commandClass */
            foreach ($commandClass::getFilesNames() as $fileName) {
                $decodedContent[$fileName] = $request->files->get($fileName);
            }
        }

        $violations = new ConstraintViolationList();
        /** @var ClassMetadataInterface $classMetadata */
        $classMetadata = $this->validator->getMetadataFor($commandClass);

        foreach ($classMetadata->getConstrainedProperties() as $propertyName) {
            $violations->addAll($this->validator->startContext()->atPath($propertyName)->validate(
                $decodedContent[$propertyName] ?? null,
                $classMetadata->getPropertyMetadata($propertyName)[0]->getConstraints()
            )->getViolations());
        }

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        yield $this->denormalizer->denormalize($decodedContent, $commandClass);
    }
}
