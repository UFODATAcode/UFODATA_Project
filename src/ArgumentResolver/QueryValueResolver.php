<?php

namespace App\ArgumentResolver;

use App\Contract\PaginatedQueryInterface;
use App\Contract\QueryInterface;
use App\Exception\ValidationException;
use App\ValueObject\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QueryValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly DenormalizerInterface $denormalizer,
    ) {}

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        if ($type === null) {
            return false;
        }

        return \in_array(QueryInterface::class, \class_implements($type));
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $queryClass = $argument->getType();
        $query = [];

        if (null !== ($uuid = $request->get('uuid'))) {
            $query['uuid'] = $uuid;
        }

        if (\in_array(PaginatedQueryInterface::class, \class_implements($queryClass))) {
            $pagination = new Pagination();
            $pagination->setLimit($request->query->getInt('limit', Pagination::DEFAULT_LIMIT));
            $pagination->setCurrentPage($request->query->getInt('page', Pagination::FIRST_PAGE));

            $query['pagination'] = $pagination;
        }

        //TODO: extract this piece of logic from both classes that are using it
        $violations = new ConstraintViolationList();
        /** @var ClassMetadataInterface $classMetadata */
        $classMetadata = $this->validator->getMetadataFor($queryClass);

        foreach ($classMetadata->getConstrainedProperties() as $propertyName) {
            $violations->addAll($this->validator->startContext()->atPath($propertyName)->validate(
                $query[$propertyName] ?? null,
                $classMetadata->getPropertyMetadata($propertyName)[0]->getConstraints()
            )->getViolations());
        }

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        yield $this->denormalizer->denormalize($query, $queryClass);
    }
}
