<?php

namespace App\ArgumentResolver;

use App\Contract\AnonymousUserInterface;
use App\Contract\CommandInterface;
use App\Contract\FileUploadInterface;
use App\Contract\UserInterface;
use App\Entity\AnonymousUser;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly DenormalizerInterface $denormalizer,
        private readonly Security $security,
    ) {}

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        if ($type === null || $type === 'bool') {
            return false;
        }

        return \in_array(CommandInterface::class, \class_implements($type));
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

            foreach ($request->request->all() as $requestItemKey => $requestItemValue) {
                $decodedContent[$requestItemKey] = $requestItemValue;
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

        $command = $this->denormalizer->denormalize($decodedContent, $commandClass);
        $command->provider = match ((new \ReflectionProperty($commandClass, 'provider'))->getType()->getName()) {
            UserInterface::class => $this->security->getUser(),
            AnonymousUserInterface::class => new AnonymousUser(),
        };

        yield $command;
    }
}
