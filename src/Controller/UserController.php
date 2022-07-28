<?php

namespace App\Controller;

use App\Command\AddUserCommand;
use App\Command\DeleteUserCommand;
use App\Handler\AddUserHandler;
use App\Handler\DeleteUserHandler;
use App\Handler\GetUserHandler;
use App\Handler\GetUsersHandler;
use App\Query\GetUserQuery;
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
        private readonly GetUserHandler $getUserHandler,
        private readonly DeleteUserHandler $deleteUserHandler,
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

    #[Route(path: '/{uuid}', name: 'get_user', methods: Request::METHOD_GET)]
    public function getUserDetails(GetUserQuery $query): JsonResponse
    {
        return new JsonResponse($this->getUserHandler->__invoke($query), Response::HTTP_OK);
    }

    #[Route(path: '/{uuid}', name: 'delete_user', methods: Request::METHOD_DELETE)]
    public function deleteUserDetails(DeleteUserCommand $command): Response
    {
        $this->deleteUserHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
