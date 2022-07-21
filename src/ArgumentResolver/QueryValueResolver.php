<?php

namespace App\ArgumentResolver;

use App\Contract\QueryInterface;
use App\Exception\ValidationException;
use App\ValueObject\Pagination;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QueryValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {}

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return \in_array(QueryInterface::class, \class_implements($argument->getType()));
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $query = new ($argument->getType());

        if (null !== ($uuid = $request->get('uuid'))) {
            $query->uuid = $uuid;
        }

        $pagination = new Pagination();
        $pagination->setLimit($request->query->getInt('limit', Pagination::DEFAULT_LIMIT));
        $pagination->setCurrentPage($request->query->getInt('page', Pagination::FIRST_PAGE));

        $query->pagination = $pagination;

        $violations = $this->validator->validate($query);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        yield $query;
    }
}
