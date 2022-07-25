<?php

namespace App\Controller;

use App\Command\AddUserCommand;
use App\Handler\AddUserHandler;
use App\Handler\GetUsersHandler;
use App\Query\GetUsersQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly AddUserHandler $addUserHandler,
        private readonly GetUsersHandler $getUsersHandler,
    ) {}

    #[Route(name: 'add_user', methods: Request::METHOD_POST)]
    public function addUser(AddUserCommand $command): Response
    {
        $this->addUserHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(name: 'get_users', methods: Request::METHOD_GET)]
    public function getUsers(GetUsersQuery $query): JsonResponse
    {
        return new JsonResponse($this->getUsersHandler->__invoke($query), Response::HTTP_OK);
    }
}
