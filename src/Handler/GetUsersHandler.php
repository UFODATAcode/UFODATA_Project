<?php

namespace App\Handler;

use App\Contract\GetUsersQueryInterface;
use App\Contract\UserRepositoryInterface;
use App\Entity\User;
use App\Response\GetResourcesResponse;
use App\Response\UserResponse;

class GetUsersHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(GetUsersQueryInterface $query): GetResourcesResponse
    {
        return new GetResourcesResponse(
            \array_map(
                fn(User $user) => new UserResponse(
                    $user->getUuid(),
                    $user->getEmail(),
                    $user->getName(),
                    $user->getRoles(),
                ),
                $this->userRepository->findAllForList($query->getPagination())
            ),
            $query->getPagination(),
        );
    }
}
