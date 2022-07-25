<?php

namespace App\Controller;

use App\Command\AddUserCommand;
use App\Handler\AddUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly AddUserHandler $addUserHandler,
    ) {}

    #[Route(name: 'add_user', methods: Request::METHOD_POST)]
    public function addUser(AddUserCommand $command): Response
    {
        $this->addUserHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
