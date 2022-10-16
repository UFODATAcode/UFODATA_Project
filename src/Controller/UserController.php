<?php

namespace App\Controller;

use App\Command\ActivateUserAccountCommand;
use App\Command\AddUserCommand;
use App\Command\DeleteUserCommand;
use App\Command\RegisterUserCommand;
use App\Handler\AddUserHandler;
use App\Handler\DeleteUserHandler;
use App\Handler\GetUserHandler;
use App\Handler\GetUsersHandler;
use App\Handler\RegisterUserHandler;
use App\Query\GetUserQuery;
use App\Query\GetUsersQuery;
use App\Response\ListUserResponse;
use App\Response\UserResponse;
use App\ValueObject\Pagination;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly AddUserHandler $addUserHandler,
        private readonly GetUsersHandler $getUsersHandler,
        private readonly GetUserHandler $getUserHandler,
        private readonly DeleteUserHandler $deleteUserHandler,
        private readonly RegisterUserHandler $registerUserHandler,
        private readonly MessageBusInterface $messageBus,
    ) {}

    #[Route(name: 'add_user', methods: Request::METHOD_POST)]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            ref: new Model(type: AddUserCommand::class),
        )
    )]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Successfully obtained the command.')]
    public function addUser(AddUserCommand $command): Response
    {
        $this->addUserHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(name: 'get_users', methods: Request::METHOD_GET)]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns list of users.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(new Model(type: ListUserResponse::class)),
                ),
                new OA\Property(
                    property: 'pagination',
                    ref: new Model(type: Pagination::class),
                ),
            ],
            type: 'object',
        ),
    )]
    public function getUsers(GetUsersQuery $query): JsonResponse
    {
        return new JsonResponse($this->getUsersHandler->__invoke($query), Response::HTTP_OK);
    }

    #[Route(path: '/{uuid}', name: 'get_user', methods: Request::METHOD_GET)]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns user details.',
        content: new OA\JsonContent(ref: new Model(type: UserResponse::class)),
    )]
    public function getUserDetails(GetUserQuery $query): JsonResponse
    {
        return new JsonResponse($this->getUserHandler->__invoke($query), Response::HTTP_OK);
    }

    #[Route(path: '/{uuid}', name: 'delete_user', methods: Request::METHOD_DELETE)]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Successfully obtained the command.')]
    public function deleteUser(DeleteUserCommand $command): Response
    {
        $this->deleteUserHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/register', name: 'register_user', methods: Request::METHOD_POST)]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            ref: new Model(type: RegisterUserCommand::class),
        )
    )]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Successfully obtained the command.')]
    public function registerUser(RegisterUserCommand $command): Response
    {
        $this->registerUserHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/activate/{uuid}', name: 'activate_user_account', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            ref: new Model(type: ActivateUserAccountCommand::class),
        )
    )]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Successfully obtained the command.')]
    public function activateUserAccount(ActivateUserAccountCommand $command): Response
    {
        $this->messageBus->dispatch($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
