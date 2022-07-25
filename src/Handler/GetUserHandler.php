<?php

namespace App\Handler;

use App\Contract\GetUserQueryInterface;
use App\Contract\UserRepositoryInterface;
use App\Response\UserResponse;

class GetUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(GetUserQueryInterface $query): UserResponse
    {
        $user = $this->userRepository->findOneByUuid($query->getUserUuid());

        return new UserResponse(
            $user->getUuid(),
            $user->getName(),
        );
    }
}
